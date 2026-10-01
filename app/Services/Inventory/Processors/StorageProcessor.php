<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class StorageProcessor
{
    public function process(Asset $asset, array $storageDevices): Asset
    {
        // Elimina los registros anteriores
        $asset->storageDevices()->delete();

        foreach ($storageDevices as $disk) {

            $asset->storageDevices()->create([

                'brand' => $disk['brand'] ?? null,

                'model' => $disk['model'] ?? null,

                'serial_number' =>
                    $disk['serialNumber']
                    ?? $disk['serial']
                    ?? null,

                'firmware' =>
                    $disk['firmware']
                    ?? null,

                'capacity_gb' =>
                    $disk['capacityGb']
                    ?? $disk['capacityGB']
                    ?? null,

                'free_gb' =>
                    $disk['freeGb']
                    ?? $disk['freeGB']
                    ?? null,

                'type' =>
                    $disk['type']
                    ?? null,

                'health' =>
                    $disk['health']
                    ?? null,

            ]);
        }

        return $asset;
    }
}