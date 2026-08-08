<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class CpuProcessor
{
    public function process(Asset $asset, array $cpu): Asset
    {
        // Eliminar información anterior del procesador
        $asset->processors()->delete();

        // Crear la información actual
        $asset->processors()->create([
            'brand'         => $cpu['brand'] ?? null,
            'model'         => $cpu['model'] ?? null,
            'processor_id'  => $cpu['processorId'] ?? null,
            'socket'        => $cpu['socket'] ?? null,
            'status'        => $cpu['status'] ?? null,
            'cores'         => $cpu['cores'] ?? null,
            'threads'       => $cpu['threads'] ?? null,
            'speed_mhz'     => $cpu['speedMHz'] ?? null,
            'architecture'  => $cpu['architecture'] ?? null,
            'family'        => $cpu['family'] ?? null,
            'generation'    => $cpu['generation'] ?? null,
        ]);

        return $asset;
    }
}