<?php

namespace App\Services\Inventory;

use Illuminate\Support\Str;

final class DeviceModelResolver
{
    public function resolve(?string $manufacturer, ?string $reportedModel): ?string
    {
        $model = trim((string) $reportedModel);

        if ($model === '') {
            return null;
        }

        $manufacturerName = Str::upper(Str::ascii(trim((string) $manufacturer)));
        $modelCode = Str::upper(trim($model));

        // Lenovo machine type 11DB is the ThinkCentre M70s family.
        if (Str::contains($manufacturerName, 'LENOVO') && Str::startsWith($modelCode, '11DB')) {
            return 'ThinkCentre M70s';
        }

        return $model;
    }
}
