<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class NetworkProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $network): Asset
    {
        $values = [

            'ip_address'   => $network['iPv4'] ?? null,

            'ipv4'         => $network['iPv4'] ?? null,

            'ipv6'         => $network['iPv6'] ?? null,

            'gateway'      => $network['gateway'] ?? null,

            'dns_server'   => $network['dnsServer'] ?? null,

            'ethernet_mac' => $this->normalizeMac($network['ethernetMac'] ?? null),

            'wifi_mac'     => $this->normalizeMac($network['wifiMac'] ?? null),

            'mac_address'  => $this->normalizeMac($network['macAddress'] ?? null),

            'domain_name'  => $network['domain'] ?? null,

        ];

        // Eliminar únicamente valores nulos
        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        return $this->updateWithHistory($asset, $values);
    }

    /**
     * Normaliza una dirección MAC.
     *
     * Convierte:
     * a0:59:50:4c:b0:ad
     *
     * En:
     * A0-59-50-4C-B0-AD
     */
    private function normalizeMac(?string $mac): ?string
{
    if (empty($mac)) {
        return null;
    }

    $mac = preg_replace('/[^A-Fa-f0-9]/', '', $mac);

    if (strlen($mac) !== 12) {
        return strtoupper($mac);
    }

    return strtoupper(implode('-', str_split($mac, 2)));
}
}