<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class MotherboardProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $board): Asset
    {
        $values = [

            'board_brand'   => $board['brand'] ?? null,

            'board_model'   => $board['model'] ?? null,

            'board_serial'  => $board['serialNumber'] ?? null,

            'board_version' => $board['version'] ?? null,

            'board_status'  => $board['status'] ?? null,

        ];

        // Eliminar únicamente valores nulos
        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        return $this->updateWithHistory($asset, $values);
    }
}