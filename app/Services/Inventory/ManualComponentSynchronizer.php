<?php

namespace App\Services\Inventory;

use App\Models\Asset;

class ManualComponentSynchronizer
{
    public function syncManualComponents(
        Asset $asset,
        array $data
    ): void {

        /*
        |--------------------------------------------------------------------------
        | PROCESADOR
        |--------------------------------------------------------------------------
        */

        $processorData =
            $data['processor']
            ?? [];


        if (
            $this->hasData(
                $processorData
            )
        ) {

            $asset->processors()->updateOrCreate(

                [
                    'asset_id' =>
                    $asset->id,
                ],

                [

                    'brand' =>
                    $processorData['brand']
                        ?? null,

                    'model' =>
                    $processorData['model']
                        ?? null,

                    'processor_id' =>
                    $processorData['processor_id']
                        ?? null,

                    'socket' =>
                    $processorData['socket']
                        ?? null,

                    'status' =>
                    $processorData['status']
                        ?? null,

                    'cores' =>
                    $processorData['cores']
                        ?? null,

                    'threads' =>
                    $processorData['threads']
                        ?? null,

                    'speed_mhz' =>
                    $processorData['speed_mhz']
                        ?? null,

                    'architecture' =>
                    $processorData['architecture']
                        ?? null,

                    'family' =>
                    $processorData['family']
                        ?? null,

                    'generation' =>
                    $processorData['generation']
                        ?? null,

                ]
            );
        } else {

            $asset
                ->processors()
                ->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | RAM
        |--------------------------------------------------------------------------
        */

        $ramModules =
            $data['ram_modules']
            ?? [];


        $ramModules =
            $this->cleanCollection(
                $ramModules
            );


        /*
        | En un activo MANUAL podemos
        | reconstruir completamente los módulos.
        */

        $asset
            ->ramModules()
            ->delete();


        foreach (
            $ramModules as $ram
        ) {

            $asset->ramModules()->create([

                'manufacturer' =>
                $ram['manufacturer']
                    ?? null,

                'model' =>
                $ram['model']
                    ?? null,

                'serial_number' =>
                $ram['serial_number']
                    ?? null,

                'bank' =>
                $ram['bank']
                    ?? null,

                'slot' =>
                $ram['slot']
                    ?? null,

                'memory_type' =>
                $ram['memory_type']
                    ?? null,

                'capacity_gb' =>
                $ram['capacity_gb']
                    ?? null,

                'speed_mhz' =>
                $ram['speed_mhz']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        $storageDevices =
            $data['storage_devices']
            ?? [];


        $storageDevices =
            $this->cleanCollection(
                $storageDevices
            );


        $asset
            ->storageDevices()
            ->delete();


        foreach (
            $storageDevices as $storage
        ) {

            $asset->storageDevices()->create([

                'brand' =>
                $storage['brand']
                    ?? null,

                'model' =>
                $storage['model']
                    ?? null,

                'serial_number' =>
                $storage['serial_number']
                    ?? null,

                'firmware' =>
                $storage['firmware']
                    ?? null,

                'capacity_gb' =>
                $storage['capacity_gb']
                    ?? null,

                'free_gb' =>
                $storage['free_gb']
                    ?? null,

                'type' =>
                $storage['type']
                    ?? null,

                'health' =>
                $storage['health']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | GPU
        |--------------------------------------------------------------------------
        */

        $gpus =
            $data['gpus']
            ?? [];


        $gpus =
            $this->cleanCollection(
                $gpus
            );


        $asset
            ->gpus()
            ->delete();


        foreach (
            $gpus as $gpu
        ) {

            $asset->gpus()->create([

                'brand' =>
                $gpu['brand']
                    ?? null,

                'model' =>
                $gpu['model']
                    ?? null,

                'memory_mb' =>
                $gpu['memory_mb']
                    ?? null,

                'driver_version' =>
                $gpu['driver_version']
                    ?? null,

                'processor' =>
                $gpu['processor']
                    ?? null,

                'resolution' =>
                $gpu['resolution']
                    ?? null,

                'refresh_rate' =>
                $gpu['refresh_rate']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NETWORK
        |--------------------------------------------------------------------------
        */

        $network =
            $data['network']
            ?? [];


        if (
            $this->hasData(
                $network
            )
        ) {

            $assetNetwork =
                $asset->network()->updateOrCreate(

                    [
                        'asset_id' =>
                        $asset->id,
                    ],

                    [

                        'gateway' =>
                        $network['gateway']
                            ?? null,

                        'dns_server' =>
                        $network['dns_server']
                            ?? null,

                        'dhcp_enabled' =>
                        $network['dhcp_enabled']
                            ?? null,

                        'connected' =>
                        true,

                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | ADAPTADORES
            |--------------------------------------------------------------------------
            */

            $adapters =
                $network['adapters']
                ?? [];


            $adapters =
                $this->cleanCollection(
                    $adapters
                );


            $assetNetwork
                ->adapters()
                ->delete();


            foreach (
                $adapters as $adapter
            ) {

                $assetNetwork
                    ->adapters()
                    ->create([

                        'name' =>
                        $adapter['name']
                            ?? null,

                        'description' =>
                        $adapter['description']
                            ?? null,

                        'mac_address' =>
                        $adapter['mac_address']
                            ?? null,

                        'type' =>
                        $adapter['type']
                            ?? null,

                        'ipv4' =>
                        $adapter['ipv4']
                            ?? null,

                        'ipv6' =>
                        $adapter['ipv6']
                            ?? null,

                        'connected' =>
                        $adapter['connected']
                            ?? false,

                        'is_primary' =>
                        $adapter['is_primary']
                            ?? false,

                        'is_active' =>
                        $adapter['is_active']
                            ?? true,

                        'last_seen_at' =>
                        now(),

                    ]);
            }
        } else {

            /*
            | Si el equipo manual no tiene red,
            | eliminamos su configuración manual.
            */

            $asset
                ->network()
                ->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | BATERÍA
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Si el formulario está vacío:
        |
        | Desktop → no se crea batería.
        |
        */

        $battery =
            $data['battery']
            ?? [];


        if (
            $this->hasData(
                $battery
            )
        ) {

            /*
            | Sanitizamos runtime.
            |
            | No permitimos valores absurdos como
            | 71582788 minutos.
            */

            $runtime =
                $battery['estimated_runtime_minutes']
                ?? null;


            if (
                $runtime !== null &&
                (
                    !is_numeric($runtime) ||
                    $runtime < 0 ||
                    $runtime > 1440
                )
            ) {

                $runtime = null;
            }


            $asset->battery()->updateOrCreate(

                [
                    'asset_id' =>
                    $asset->id,
                ],

                [

                    'manufacturer' =>
                    $battery['manufacturer']
                        ?? null,

                    'model' =>
                    $battery['model']
                        ?? null,

                    'serial_number' =>
                    $battery['serial_number']
                        ?? null,

                    'health' =>
                    $battery['health']
                        ?? null,

                    'cycle_count' =>
                    $battery['cycle_count']
                        ?? null,

                    'design_capacity' =>
                    $battery['design_capacity']
                        ?? null,

                    'full_charge_capacity' =>
                    $battery['full_charge_capacity']
                        ?? null,

                    'remaining_capacity' =>
                    $battery['remaining_capacity']
                        ?? null,

                    'charge_percent' =>
                    $battery['charge_percent']
                        ?? null,

                    'is_charging' =>
                    $battery['is_charging']
                        ?? null,

                    'estimated_runtime_minutes' =>
                    $runtime,

                ]
            );
        } else {

            /*
            | Desktop o equipo sin batería.
            */

            $asset
                ->battery()
                ->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | MONITORES
        |--------------------------------------------------------------------------
        */

        $monitors =
            $data['monitors']
            ?? [];


        $monitors =
            $this->cleanCollection(
                $monitors
            );


        /*
        | Para activos manuales reconstruimos
        | la colección completa.
        */

        $asset
            ->monitors()
            ->delete();


        foreach (
            $monitors as $monitor
        ) {

            $asset->monitors()->create([

                'brand' =>
                $monitor['brand']
                    ?? null,

                'model' =>
                $monitor['model']
                    ?? null,

                'serial_number' =>
                $monitor['serial_number']
                    ?? null,

                'manufacturer_code' =>
                $monitor['manufacturer_code']
                    ?? null,

                'year' =>
                $monitor['year']
                    ?? null,

                'size' =>
                $monitor['size']
                    ?? null,

                'resolution' =>
                $monitor['resolution']
                    ?? null,

                'refresh_rate' =>
                $monitor['refresh_rate']
                    ?? null,

                'fixed_asset_code' =>
                $monitor['fixed_asset_code']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SOFTWARE
        |--------------------------------------------------------------------------
        |
        | Confirmado actualmente:
        | name + version.
        |
        */

        $software =
            $data['software']
            ?? [];


        $software =
            $this->cleanCollection(
                $software
            );


        $asset
            ->software()
            ->delete();


        foreach (
            $software as $program
        ) {

            if (
                empty(trim(
                    (string) (
                        $program['name']
                        ?? ''
                    )
                ))
            ) {
                continue;
            }


            $asset->software()->create([

                'name' =>
                strip_tags(
                    $program['name']
                ),

                'version' =>
                strip_tags(
                    $program['version']
                        ?? ''
                ),

            ]);
        }
    }


    /**
     * =========================================================
     * VERIFICAR SI UN ARREGLO TIENE INFORMACIÓN REAL
     * =========================================================
     */
    private function hasData(
        array $data
    ): bool {

        foreach (
            $data as $value
        ) {

            if (
                is_array($value)
            ) {

                if (
                    $this->hasData(
                        $value
                    )
                ) {

                    return true;
                }

                continue;
            }


            if (
                $value !== null &&
                $value !== ''
            ) {

                return true;
            }
        }


        return false;
    }


    /**
     * =========================================================
     * LIMPIAR COLECCIONES DE COMPONENTES
     * =========================================================
     */
    private function cleanCollection(
        array $items
    ): array {

        return array_values(
            array_filter(
                $items,
                function ($item) {

                    return is_array($item) &&
                        $this->hasData($item);
                }
            )
        );
    }



}
