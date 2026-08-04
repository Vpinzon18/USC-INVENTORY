<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class CpuProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $cpu): Asset
    {
        $values = [
            'cpu_brand'        => $cpu['brand'] ?? null,
            'cpu_model'        => $cpu['model'] ?? null,
            'cpu_cores'        => $cpu['cores'] ?? null,
            'cpu_threads'      => $cpu['threads'] ?? null,
            'cpu_speed_mhz'    => $cpu['speedMHz'] ?? null,
            'cpu_architecture' => $cpu['architecture'] ?? null,
        ];

        // Elimina valores inexistentes
        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        $this->updateWithHistory($asset, $values);

        // Refresca el modelo desde la base de datos
        $asset->refresh();

        return $asset;
    }
}