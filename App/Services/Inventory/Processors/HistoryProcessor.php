<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;
use App\Models\AssetHistory;

class HistoryProcessor
{

    public function logChange(
    Asset $asset,
    string $field,
    mixed $oldValue,
    mixed $newValue
    ): void {

        if ($oldValue == $newValue) {
            return;
        }

        AssetHistory::create([
            'asset_id'   => $asset->id,
            'field_name' => $field,
            'old_value'  => is_array($oldValue)
                ? json_encode($oldValue)
                : $oldValue,

            'new_value'  => is_array($newValue)
                ? json_encode($newValue)
                : $newValue,

            'source' => 'AGENT'
        ]);
    }
}