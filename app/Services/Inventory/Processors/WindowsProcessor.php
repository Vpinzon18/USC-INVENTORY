<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class WindowsProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $windows): Asset
    {
        $values = [

            'windows_version' => $windows['version'] ?? null,

            'windows_build' => $windows['build'] ?? null,

            'windows_edition' => $windows['edition'] ?? null,

            'os_version' => $windows['name'] ?? null,

            'license_status' => $windows['licenseStatus'] ?? null,

            'secure_boot' => $windows['secureBoot'] ?? false,

            'bitlocker_status' => $windows['bitlockerStatus'] ?? null,

            'tpm_version' => $windows['tpmVersion'] ?? null,

            'uptime_minutes' => $windows['uptimeMinutes'] ?? null,
        ];

        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        return $this->updateWithHistory($asset, $values);
    }
}