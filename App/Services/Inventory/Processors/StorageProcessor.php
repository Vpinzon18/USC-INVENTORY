<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class StorageProcessor
{
    public function process(Asset $asset, array $storageDevices): Asset
    {
        $asset->storageDevices()->delete();

        foreach ($storageDevices as $disk)
        {
            $asset->storageDevices()->create([

                'brand' => $disk['brand'] ?? null,

                'model' => $disk['model'] ?? null,

                'serial_number' => $disk['serial'] ?? null,

                'capacity_gb' => $disk['capacityGB'] ?? null,

                'free_gb' => $disk['freeGB'] ?? null,

                'type' => $disk['type'] ?? null,

                'health' => $disk['health'] ?? null,

            ]);
        }

        return $asset;
    }
}