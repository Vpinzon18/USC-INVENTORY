<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class BiosProcessor  extends BaseProcessor
{
    public function process(Asset $asset, array $bios): asset
    {
       $values = [

            'bios_version' => $bios['version'] ?? null,

            'bios_date' => !empty($bios['date'])
    ? date('Y-m-d', strtotime($bios['date']))
    : null,

            'manufacturer' => $bios['manufacturer'] ?? $asset->manufacturer,
];
        $values = array_filter(
    $values, static fn ($value) => $value !== null);

$this->updateWithHistory($asset, $values);

        // Refresca el modelo desde la base de datos
        $asset->refresh();

        return $asset;
    }
}
