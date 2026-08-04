<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class RamProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $ram): Asset
    {
       $values = [

    'ram'=>$ram['description'] ?? null ,

    'ram_brand'=>$ram['brand'] ?? null ,

    'ram_model'=>$ram['model'] ?? null ,

    'ram_capacity_gb'=>$ram['capacityGB'] ?? null ,

    'ram_speed'=>$ram['speedMHz'] ?? null ,

    'ram_type'=>$ram['type'] ?? null ,

    'ram_slots'=>$ram['slots'] ?? null ,

    'ram_modules'=>$ram['modules'] ?? null ,

];
$values = array_filter(
    $values, static fn ($value) => $value !== null);

$this->updateWithHistory($asset, $values);

        // Refresca el modelo desde la base de datos
        $asset->refresh();

        return $asset;
    }
}