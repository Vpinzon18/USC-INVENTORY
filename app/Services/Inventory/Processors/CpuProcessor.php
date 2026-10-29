<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class CpuProcessor
{
    public function process(Asset $asset, array $cpu): Asset
    {
        $values = array_filter([
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
        ], static fn ($value, $field) => $value !== null
            && $value !== ''
            && (!in_array($field, ['cores', 'threads', 'speed_mhz'], true) || (int) $value > 0),
            ARRAY_FILTER_USE_BOTH);

        // Un reporte sin datos útiles suele indicar que falló la recolección.
        // Conservamos el último procesador válido en ese caso.
        if ($values === []) {
            return $asset;
        }

        $processor = $asset->processors()->orderBy('id')->first();
        if ($processor) {
            $processor->update($values);

            // Mantener una sola fila canónica, después de guardar una lectura válida.
            $asset->processors()->where('id', '<>', $processor->getKey())->delete();
        } else {
            $asset->processors()->create($values);
        }

        return $asset;
    }
}
