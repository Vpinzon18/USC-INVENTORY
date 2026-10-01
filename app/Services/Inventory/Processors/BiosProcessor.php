<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;
use Illuminate\Support\Facades\Log;

class BiosProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $bios): Asset
    {
        Log::info('BIOS RECIBIDO POR LARAVEL', [
            'asset_id' => $asset->id,
            'bios' => $bios,
        ]);

        $values = [

            'bios_version' =>
                $bios['version'] ?? null,

            'bios_manufacturer' =>
                $bios['manufacturer'] ?? null,

            'bios_serial' =>
                $bios['serialNumber'] ?? null,

            'bios_release_date' =>
                !empty($bios['releaseDate'])
                    ? date(
                        'Y-m-d',
                        strtotime($bios['releaseDate'])
                    )
                    : null,
        ];

        Log::info('BIOS TRANSFORMADO', [
            'asset_id' => $asset->id,
            'values' => $values,
        ]);

        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        $this->updateWithHistory(
            $asset,
            $values
        );

        $asset->refresh();

        return $asset;
    }
}