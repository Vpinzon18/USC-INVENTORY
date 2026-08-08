<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class GpuProcessor
{
    public function process(Asset $asset, array $gpus): Asset
    {
        // Eliminar las GPU anteriores del equipo
        $asset->gpus()->delete();

        foreach ($gpus as $gpu) {
            $asset->gpus()->create([
                'brand'          => $gpu['brand'] ?? null,
                'model'          => $gpu['model'] ?? null,
                'memory_mb'      => $gpu['memoryMB'] ?? null,
                'driver_version' => $gpu['driverVersion'] ?? null,
                'processor'      => $gpu['processor'] ?? null,
                'resolution'     => $gpu['resolution'] ?? null,
                'refresh_rate'   => $gpu['refreshRate'] ?? null,
            ]);
        }

        return $asset;
    }
}