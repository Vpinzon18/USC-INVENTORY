<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class MonitorProcessor
{
    public function process(Asset $asset, array $monitors): Asset
    {
        // Elimina los monitores existentes
        $asset->monitors()->delete();

        foreach ($monitors as $monitor) {

            $asset->monitors()->create([

                'brand' => $monitor['brand'] ?? null,

                'model' => $monitor['model'] ?? null,

                'serial_number' => $monitor['serial'] ?? null,

                'manufacturer_code' => $monitor['manufacturerCode'] ?? null,

                'year' => $monitor['year'] ?? null,

                'size' => $monitor['size'] ?? null,

                'resolution' => $monitor['resolution'] ?? null,

                'refresh_rate' => $monitor['refreshRate'] ?? null,

            ]);
        }

        return $asset;
    }
}