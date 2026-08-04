<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;
use App\Models\Software;

class SoftwareProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $inventory): Asset
    {
        if (
            !isset($inventory['software']) ||
            !is_array($inventory['software'])
        ) {
            return $asset;
        }

        $softwareList = $inventory['software'];

        /*
        |--------------------------------------------------------
        | Marcar todos como inactivos
        |--------------------------------------------------------
        */
        Software::where('asset_id', $asset->id)
            ->update([
                'is_active' => false,
            ]);

        /*
        |--------------------------------------------------------
        | Sincronizar software instalado
        |--------------------------------------------------------
        */
        foreach ($softwareList as $program) {

            if (empty($program['name'])) {
                continue;
            }

            Software::updateOrCreate(

                [
                    'asset_id' => $asset->id,
                    'name'     => trim($program['name']),
                ],

                [
                    'version'           => $program['version'] ?? null,
                    'publisher'         => $program['publisher'] ?? null,
                    'install_date'      => !empty($program['installDate'])
                        ? date('Y-m-d', strtotime($program['installDate']))
                        : null,
                    'install_location'  => $program['installLocation'] ?? null,
                    'estimated_size_mb' => $program['estimatedSizeMB'] ?? null,
                    'architecture'      => $program['architecture'] ?? null,
                    'is_active'         => true,
                ]
            );
        }

        return $asset->fresh();
    }
}