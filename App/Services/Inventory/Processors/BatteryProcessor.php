<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class BatteryProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $battery): Asset
    {
        if (empty($battery)) {
            return $asset;
        }

        $asset->battery()->updateOrCreate(
            ['asset_id' => $asset->id],
            [
                'manufacturer' => $battery['manufacturer'] ?? null,
                'model' => $battery['model'] ?? null,
                'serial_number' => $battery['serial_number'] ?? null,

                'health' => $battery['health'] ?? null,
                'cycle_count' => $battery['cycle_count'] ?? null,

                'design_capacity' => $battery['design_capacity'] ?? null,
                'full_charge_capacity' => $battery['full_charge_capacity'] ?? null,
                'remaining_capacity' => $battery['remaining_capacity'] ?? null,

                'charge_percent' => $battery['charge_percent'] ?? null,
                'is_charging' => $battery['is_charging'] ?? null,
                'estimated_runtime_minutes' => $battery['estimated_runtime_minutes'] ?? null,
            ]
        );

        return $asset;
    }
}