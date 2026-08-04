<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class DeviceProcessor
{
    public function process(array $device): Asset
    {
        return Asset::updateOrCreate(

            [
                'serial_number' => $device['serialNumber']
            ],

            [
                'hostname'        => $device['hostname'] ?? null,
                'model_version'   => $device['model'] ?? null,
                'last_seen_at'    => now(),

                'is_agent_managed' => true,
            ]
        );
    }
}