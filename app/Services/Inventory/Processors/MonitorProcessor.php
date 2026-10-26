<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class MonitorProcessor
{
    public function process(Asset $asset, array $monitors): Asset
    {
        foreach ($monitors as $monitor) {
            $serialNumber = trim((string) ($monitor['serial'] ?? ''));
            $values = array_filter([
                'brand' => $monitor['brand'] ?? null,
                'model' => $monitor['model'] ?? null,
                'manufacturer_code' => $monitor['manufacturerCode'] ?? null,
                'year' => $monitor['year'] ?? null,
                'size' => $monitor['size'] ?? null,
                'resolution' => $monitor['resolution'] ?? null,
                'refresh_rate' => $monitor['refreshRate'] ?? null,
            ], static fn ($value) => $value !== null && $value !== '');

            if ($serialNumber === '') {
                // Some internal laptop panels do not expose an EDID serial.
                // Reuse the matching serial-less record to avoid duplicates.
                $brand = $values['brand'] ?? null;
                $model = $values['model'] ?? null;
                if (!$brand && !$model) {
                    continue;
                }

                $existing = $asset->monitors()
                    ->whereNull('serial_number')
                    ->when($brand, fn ($query) => $query->where('brand', $brand))
                    ->when($model, fn ($query) => $query->where('model', $model))
                    ->first();

                if ($existing) {
                    $existing->update($values);
                } else {
                    $asset->monitors()->create(['serial_number' => null] + $values);
                }

                continue;
            }

            $asset->monitors()->updateOrCreate(
                ['serial_number' => $serialNumber],
                $values
            );
        }

        return $asset;
    }
}
