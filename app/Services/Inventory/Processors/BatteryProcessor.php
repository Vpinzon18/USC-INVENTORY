<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class BatteryProcessor
{
    public function process(Asset $asset, array $battery): Asset
    {
        if (empty($battery)) {
            return $asset;
        }

        $asset->battery()->updateOrCreate(
            [
                'asset_id' => $asset->id,
            ],
            [
                'manufacturer' => $battery['manufacturer'] ?? null,
                'model' => $battery['model'] ?? null,
                'serial_number' => $battery['serialNumber'] ?? null,
                'health' => $battery['health'] ?? null,
                'cycle_count' => $battery['cycleCount'] ?? null,
                'design_capacity' => $battery['designCapacity'] ?? null,
                'full_charge_capacity' => $battery['fullChargeCapacity'] ?? null,
                'remaining_capacity' => $battery['remainingCapacity'] ?? null,
                'charge_percent' => $battery['chargePercent'] ?? null,
                'is_charging' => $battery['isCharging'] ?? null,
                'estimated_runtime_minutes' => $battery['estimatedRuntimeMinutes'] ?? null,
            ]
        );

        return $asset;
    }
}