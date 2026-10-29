<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class SecurityProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $security): Asset
    {
        $values = [

            'secure_boot' => $security['secureBoot'] ?? null,

            'bitlocker_status' => $security['bitlockerStatus'] ?? null,

            'tpm_version' => $security['tpmVersion'] ?? null,

            'antivirus' => $security['antivirus'] ?? null,

            'firewall_enabled' => $security['firewallEnabled'] ?? null,
        ];

        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        return $this->updateWithHistory($asset, $values);
    }
}
