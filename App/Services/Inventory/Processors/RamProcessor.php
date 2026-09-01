<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class RamProcessor
{
    public function process(Asset $asset, array $ramModules): Asset
    {
        $asset->ramModules()->delete();

        foreach ($ramModules as $module) {
            $asset->ramModules()->create([
                'manufacturer'  => $module['manufacturer'] ?? null,
                'model'         => $module['model'] ?? null,
                'serial_number' => $module['serial'] ?? null,
                'bank'          => $module['bank'] ?? null,
                'slot'          => $module['slot'] ?? null,
                'memory_type'   => $module['memoryType'] ?? null,
                'capacity_gb'   => $module['capacityGB'] ?? null,
                'speed_mhz'     => $module['speedMHz'] ?? null,
            ]);
        }

        return $asset;
    }
}