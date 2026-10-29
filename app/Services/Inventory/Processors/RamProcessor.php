<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class RamProcessor
{
    public function process(Asset $asset, array $ramModules): Asset
    {
        $modules = collect($ramModules)
            ->filter(static fn ($module) => is_array($module))
            ->map(static fn (array $module) => [
                'manufacturer'  => $module['manufacturer'] ?? null,
                'model'         => $module['model'] ?? null,
                'serial_number' => $module['serial'] ?? null,
                'bank'          => $module['bank'] ?? null,
                'slot'          => $module['slot'] ?? null,
                'memory_type'   => $module['memoryType'] ?? null,
                'capacity_gb'   => $module['capacityGB'] ?? null,
                'speed_mhz'     => $module['speedMHz'] ?? null,
            ])
            ->filter(static fn (array $module) => collect($module)->contains(
                static fn ($value) => $value !== null && $value !== ''
            ))
            ->values();

        // Una lista vacía o sin identificadores/datos puede ser una falla de
        // lectura; no debe borrar los módulos que ya conocemos.
        if ($modules->isEmpty()) {
            return $asset;
        }

        $asset->ramModules()->delete();

        foreach ($modules as $module) {
            $asset->ramModules()->create($module);
        }

        return $asset;
    }
}
