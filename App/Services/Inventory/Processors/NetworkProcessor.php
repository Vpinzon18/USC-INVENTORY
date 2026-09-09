<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class NetworkProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $network): Asset
    {
        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN GENERAL DE RED
        |--------------------------------------------------------------------------
        */

        $networkData = [
            'gateway' => $network['gateway'] ?? null,

            'dns_server' => $network['dnsServer'] ?? null,

            'dhcp_enabled' => $network['dhcpEnabled'] ?? false,

            'connected' => $network['connected'] ?? false,
        ];

        /*
        |--------------------------------------------------------------------------
        | CREAR / ACTUALIZAR RED DEL EQUIPO
        |--------------------------------------------------------------------------
        */

        $assetNetwork = $asset->network()->updateOrCreate(
            [
                'asset_id' => $asset->id,
            ],
            $networkData
        );

        /*
        |--------------------------------------------------------------------------
        | ADAPTADORES
        |--------------------------------------------------------------------------
        |
        | No eliminamos los adaptadores existentes.
        |
        | La MAC funciona como identificador lógico del adaptador:
        |
        |   MAC existente -> UPDATE
        |   MAC nueva     -> CREATE
        |
        */

        $adapters = $network['adapters'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Marcar adaptadores anteriores como inactivos
        |--------------------------------------------------------------------------
        |
        | Los adaptadores que aparezcan nuevamente serán marcados
        | como activos más adelante.
        |
        */

        $assetNetwork->adapters()->update([
            'is_active' => false,
            'connected' => false,
            'is_primary' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SINCRONIZAR ADAPTADORES
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | DATOS DEL ADAPTADOR
            |--------------------------------------------------------------------------
            */

            $adapterData = [

                'name' =>
                    $adapter['name'] ?? null,

                'description' =>
                    $adapter['description'] ?? null,

                'mac_address' =>
                    $mac,

                'type' =>
                    $adapter['type'] ?? null,

                'ipv4' =>
                    $adapter['iPv4']
                    ?? $adapter['ipv4']
                    ?? null,

                'ipv6' =>
                    $adapter['iPv6']
                    ?? $adapter['ipv6']
                    ?? null,

                'connected' =>
                    $adapter['connected'] ?? false,

                'is_primary' =>
                    $adapter['isPrimary']
                    ?? $adapter['is_primary']
                    ?? false,

                'is_active' => true,

                'last_seen_at' => now(),
            ];

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