<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class NetworkProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $network): Asset
    {
        $hasCompletionMarker = array_key_exists('collectionSucceeded', $network);
        $collectionSucceeded = ($network['collectionSucceeded'] ?? false) === true;
        if ($hasCompletionMarker && !$collectionSucceeded) {
            return $asset;
        }
        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN GENERAL DE RED
        |--------------------------------------------------------------------------
        */

        $networkData = [];
        foreach ([
            'gateway' => 'gateway',
            'dnsServer' => 'dns_server',
            'dhcpEnabled' => 'dhcp_enabled',
            'connected' => 'connected',
        ] as $input => $column) {
            if (array_key_exists($input, $network)
                && $network[$input] !== null
                && (!is_string($network[$input]) || trim($network[$input]) !== '')
                && ($input !== 'dhcpEnabled' || $hasCompletionMarker)
                && ($input !== 'connected' || $hasCompletionMarker || !empty($network['adapters']))) {
                $networkData[$column] = $network[$input];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREAR / ACTUALIZAR RED DEL EQUIPO
        |--------------------------------------------------------------------------
        */

        $adapters = isset($network['adapters']) && is_array($network['adapters'])
            ? array_values(array_filter($network['adapters'], 'is_array'))
            : [];
        $hasAdapterSnapshot = $adapters !== [];
        $hasAuthoritativeAdapterSnapshot = $hasCompletionMarker && $collectionSucceeded;

        $assetNetwork = $networkData !== [] || $hasAdapterSnapshot || $hasAuthoritativeAdapterSnapshot
            ? $asset->network()->updateOrCreate(['asset_id' => $asset->id], $networkData)
            : $asset->network;

        // El agente obtiene el dominio desde Win32_ComputerSystem y lo incluye
        // en `network.domain`; guardarlo en assets.domain_name mantiene el dato
        // que consulta la ficha de vida.
        $primaryAdapter = collect($adapters)->first(
            static fn (array $adapter) => (bool) ($adapter['isPrimary'] ?? $adapter['is_primary'] ?? false)
        );
        $primaryAdapter ??= collect($adapters)->first(
            static fn (array $adapter) => !empty($adapter['macAddress'])
                && !empty($adapter['iPv4'] ?? $adapter['ipv4'] ?? null)
                && ($adapter['connected'] ?? true)
        );
        $primaryAdapter ??= collect($adapters)->first(
            static fn (array $adapter) => !empty($adapter['macAddress'])
                && ($adapter['connected'] ?? true)
        );

        $assetNetworkValues = [];
        $domainName = trim((string) ($network['domain'] ?? ''));
        if ($domainName !== '') {
            $assetNetworkValues['domain_name'] = $domainName;
        }

        $primaryIp = trim((string) (
            $primaryAdapter['iPv4']
            ?? $primaryAdapter['ipv4']
            ?? $network['ipv4']
            ?? ''
        ));
        $primaryMac = $this->normalizeMac(
            $primaryAdapter['macAddress']
            ?? $network['macAddress']
            ?? null
        );

        if ($assetNetworkValues !== []) {
            $asset = $this->updateWithHistory($asset, $assetNetworkValues);
        }

        if (!$assetNetwork || (!$hasAdapterSnapshot && !$hasAuthoritativeAdapterSnapshot)) {
            return $asset->load(['network', 'network.adapters']);
        }

        /*
        |--------------------------------------------------------------------------
        | ADAPTADORES
        |--------------------------------------------------------------------------
        |
        | No eliminamos el historial de adaptadores. Solo marcamos inactivos
        | los que no aparecen en una lectura no vacía.
        |
        | La MAC funciona como identificador lógico del adaptador:
        |
        |   MAC existente -> UPDATE
        |   MAC nueva     -> CREATE
        |
        */

        /*
        |--------------------------------------------------------------------------
        | SINCRONIZAR ADAPTADORES
        |--------------------------------------------------------------------------
        */

        $seenMacs = [];
        foreach ($adapters as $adapter) {

            /*
            |--------------------------------------------------------------------------
            | NORMALIZAR MAC
            |--------------------------------------------------------------------------
            */

            $mac = $this->normalizeMac(
                $adapter['macAddress'] ?? null
            );

            /*
            |--------------------------------------------------------------------------
            | Sin MAC no podemos identificar correctamente
            | el adaptador.
            |--------------------------------------------------------------------------
            */

            if (empty($mac)) {
                continue;
            }

            $seenMacs[] = $mac;

            /*
            |--------------------------------------------------------------------------
            | DATOS DEL ADAPTADOR
            |--------------------------------------------------------------------------
            */

            $existingAdapter = $assetNetwork->adapters()
                ->where('mac_address', $mac)
                ->exists();
            $adapterData = [
                'mac_address' => $mac,
                'is_active' => true,
                'last_seen_at' => now(),
            ];

            foreach ([
                'name' => 'name',
                'description' => 'description',
                'type' => 'type',
                'connected' => 'connected',
            ] as $input => $column) {
                if (array_key_exists($input, $adapter)
                    && $adapter[$input] !== null
                    && (!is_string($adapter[$input]) || trim($adapter[$input]) !== '')) {
                    $adapterData[$column] = $adapter[$input];
                }
            }

            $ipv4 = $adapter['iPv4'] ?? $adapter['ipv4']
                ?? ($primaryMac !== null && $mac === $primaryMac ? $primaryIp : null);
            $ipv6 = $adapter['iPv6'] ?? $adapter['ipv6'] ?? null;
            if ($ipv4 !== null) {
                $adapterData['ipv4'] = $ipv4;
            }
            if ($ipv6 !== null) {
                $adapterData['ipv6'] = $ipv6;
            }

            if ($primaryMac !== null) {
                $adapterData['is_primary'] = $mac === $primaryMac;
            } elseif (array_key_exists('isPrimary', $adapter) || array_key_exists('is_primary', $adapter)) {
                $adapterData['is_primary'] = (bool) ($adapter['isPrimary'] ?? $adapter['is_primary']);
            }

            if (!$existingAdapter) {
                $adapterData += [
                    'connected' => (bool) ($adapter['connected'] ?? false),
                    'is_primary' => $adapterData['is_primary'] ?? false,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR O CREAR
            |--------------------------------------------------------------------------
            |
            | La relación ya limita la búsqueda al asset_network
            | correspondiente.
            |
            */

            $assetNetwork->adapters()->updateOrCreate(
                [
                    'mac_address' => $mac,
                ],
                $adapterData
            );
        }

        if ($hasAuthoritativeAdapterSnapshot) {
            $staleAdapters = $assetNetwork->adapters();
            if ($seenMacs !== []) {
                $staleAdapters->whereNotIn('mac_address', array_unique($seenMacs));
            }
            $staleAdapters->update([
                'is_active' => false,
                'connected' => false,
                'is_primary' => false,
            ]);
        } elseif ($seenMacs !== []) {
            // Payloads de agentes anteriores no son snapshots autoritativos:
            // se incorporan sus adaptadores, pero no se desactivan los demás.
            $assetNetwork->adapters()
                ->whereIn('mac_address', array_unique($seenMacs))
                ->update(['is_active' => true, 'last_seen_at' => now()]);
        }

        /*
        |--------------------------------------------------------------------------
        | REFRESCAR RELACIONES
        |--------------------------------------------------------------------------
        */

        $asset->load([
            'network',
            'network.adapters',
        ]);

        return $asset;
    }

    /**
     * Normaliza una dirección MAC.
     *
     * Convierte:
     *
     * a0:59:50:4c:b0:ad
     *
     * a:
     *
     * A0:59:50:4C:B0:AD
     */
    private function normalizeMac(?string $mac): ?string
    {
        if (empty($mac)) {
            return null;
        }

        $mac = preg_replace(
            '/[^A-Fa-f0-9]/',
            '',
            $mac
        );

        if (strlen($mac) !== 12) {
            return strtoupper($mac);
        }

        return strtoupper(
            implode(
                ':',
                str_split($mac, 2)
            )
        );
    }
}
