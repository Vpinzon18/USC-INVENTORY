<?php

namespace App\Services\Assets;

use App\Models\Asset;
use App\Services\Inventory\DeviceModelResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class AssetPdfDataBuilder
{
    public function __construct(private readonly DeviceModelResolver $modelResolver)
    {
    }

    /**
     * Build the display data for both the browser preview and the official PDF.
     * Component collections are already eager-loaded by AssetPdfController.
     */
    public function build(Asset $asset): array
    {
        $custodian = $asset->currentCustodian ?? $asset->custodian;
        $processors = $asset->processors ?? collect();
        $ramModules = $asset->ramModules ?? collect();
        $storageDevices = $asset->storageDevices ?? collect();
        $gpus = $asset->gpus ?? collect();
        $adapters = $asset->network?->adapters ?? collect();
        $monitors = $asset->monitors ?? collect();
        $software = ($asset->software ?? collect())
            ->sortBy(fn ($application) => Str::lower((string) $application->name))
            ->take(28)
            ->values();
        $history = ($asset->technicalServices ?? collect())->take(24)->values();

        $wirelessAdapters = $adapters->filter(function ($adapter): bool {
            $adapterDetails = Str::lower(implode(' ', array_filter([
                $adapter->name,
                $adapter->description,
                $adapter->type,
            ])));

            return Str::contains($adapterDetails, [
                'wifi', 'wi-fi', 'wireless', 'inalambr', '802.11',
            ]);
        });

        $networkPriority = fn ($adapter): int =>
            ((bool) $adapter->is_primary ? 4 : 0)
            + ((bool) $adapter->connected ? 2 : 0)
            + ((bool) $adapter->is_active ? 1 : 0);

        $preferredAdapter = $adapters->sortByDesc($networkPriority)->first();
        $ipAdapter = $adapters->filter(fn ($adapter) => filled($adapter->ipv4))
            ->sortByDesc($networkPriority)
            ->first();
        $macAdapter = $adapters->filter(fn ($adapter) => filled($adapter->mac_address))
            ->sortByDesc($networkPriority)
            ->first();

        $campusName = trim((string) (
            $asset->room?->building?->campus?->name ?? ''
        ));
        $normalizedCampus = $this->normalize($campusName);
        $isPampalinda = Str::contains($normalizedCampus, 'PAMPALINDA');
        $isPalmira = Str::contains($normalizedCampus, 'PALMIRA');
        $isCentro = Str::contains($normalizedCampus, 'CENTRO');
        $isOtherSite = $normalizedCampus !== ''
            && !$isPampalinda
            && !$isPalmira
            && !$isCentro;

        $domainName = $this->normalize((string) $asset->domain_name);
        $domainIsAdmin = Str::contains($domainName, 'ADMIN');
        $domainIsAcademic = Str::contains($domainName, 'ACAD');
        $domainIsNo = !$domainIsAdmin && !$domainIsAcademic;

        $boardDescription = $this->joinParts([$asset->board_model]);

        $processorDescription = $this->joinLines($processors, fn ($processor) => $processor->model);

        if ($processorDescription === 'N/A') {
            $processorDescription = $this->joinParts([$asset->cpu_model]);
        }

        $ramCapacity = $ramModules->sum(fn ($module) => (float) ($module->capacity_gb ?? 0));
        $ramTypes = $this->joinDistinct($ramModules, fn ($module) => $module->memory_type);
        $ramDescription = $this->joinParts([
            $ramCapacity > 0 ? $this->formatCapacity($ramCapacity).' GB EN TOTAL' : null,
            $ramTypes !== 'N/A' ? $ramTypes : null,
        ]);

        if ($ramDescription === 'N/A' && $asset->ram_capacity_gb) {
            $ramDescription = $asset->ram_capacity_gb.' GB';
        }

        $storageDescription = $this->joinLines($storageDevices, function ($device): string {
            return $this->joinParts([
                $device->model,
                $device->capacity_gb ? $device->capacity_gb.' GB' : null,
            ]);
        });

        $wirelessDescription = $this->joinLines($wirelessAdapters, fn ($adapter) => $adapter->name);
        $wirelessBrand = $this->joinDistinct($wirelessAdapters, fn ($adapter) => Str::before(trim((string) $adapter->name), ' '));
        if ($wirelessDescription === 'N/A') {
            $wirelessDescription = $this->joinParts([$asset->wifi_model]);
            $wirelessBrand = $this->joinParts([$asset->wifi_brand]);
        }

        $graphicsDescription = $this->joinLines($gpus, fn ($gpu) => $gpu->model ?: $gpu->processor);

        $guayaValue = $asset->getAttribute('security_guaya');
        $guayaDescription = $this->describeSecurityGuaya($guayaValue);

        $operatingSystem = $this->joinParts([
            $asset->windows_edition,
            $asset->windows_version,
            $asset->os_version,
        ]);

        $historyTechnician = $history->first(fn ($entry) => filled($entry->technician?->name));
        $softwareRows = collect(range(0, 13))->map(function (int $index) use ($software): array {
            return [$software->get($index), $software->get($index + 14)];
        });

        $monitorAssetCodes = $this->joinDistinct($monitors, fn ($monitor) =>
            $monitor->getAttribute('fixed_asset_code') ?: $monitor->manufacturer_code
        );
        $monitorSerials = $this->joinDistinct($monitors, fn ($monitor) => $monitor->serial_number);

        $documentNumber = trim((string) ($custodian?->document_number ?? ''));
        $observations = $this->joinParts([
            $asset->getAttribute('observations'),
            $asset->getAttribute('notes'),
        ]);

        return [
            'custodian' => $custodian,
            'custodianDocument' => $documentNumber !== '' ? $documentNumber : 'N/A',
            'campusName' => $campusName,
            'isPampalinda' => $isPampalinda,
            'isPalmira' => $isPalmira,
            'isCentro' => $isCentro,
            'isOtherSite' => $isOtherSite,
            'siteOtherLabel' => $isOtherSite ? $campusName : '',
            'referenceModel' => $this->joinParts([
                $asset->manufacturer,
                $this->modelResolver->resolve($asset->manufacturer, $asset->model_version),
            ]),
            'monitorAssetCodes' => $monitorAssetCodes,
            'monitorSerials' => $monitorSerials,
            'hardwareRows' => [
                ['label' => 'BOARD', 'description' => $boardDescription, 'brand' => $this->joinParts([$asset->board_brand])],
                ['label' => 'PROCESADOR', 'description' => $processorDescription, 'brand' => $this->joinDistinct($processors, fn ($processor) => $processor->brand)],
                ['label' => 'RAM', 'description' => $ramDescription, 'brand' => $this->joinDistinct($ramModules, fn ($module) => $module->manufacturer)],
                ['label' => 'DISCO DURO', 'description' => $storageDescription, 'brand' => $this->joinDistinct($storageDevices, fn ($device) => $device->brand)],
                ['label' => 'TARJETA INALÁMBRICA', 'description' => $wirelessDescription, 'brand' => $wirelessBrand],
                ['label' => 'TARJETA GRÁFICA', 'description' => $graphicsDescription, 'brand' => $this->joinDistinct($gpus, fn ($gpu) => $gpu->brand)],
                ['label' => 'GUAYA DE SEGURIDAD', 'description' => $guayaDescription, 'brand' => 'N/A'],
                ['label' => 'OTROS', 'description' => $operatingSystem, 'brand' => $operatingSystem !== 'N/A' ? 'MICROSOFT' : 'N/A'],
            ],
            'softwareRows' => $softwareRows,
            'history' => $history,
            'technician' => $historyTechnician?->technician?->name ?? 'N/A',
            'observations' => $observations,
            'primaryAdapter' => $preferredAdapter,
            'ipAddress' => $ipAdapter?->ipv4 ?? 'N/A',
            'macAddress' => $macAdapter?->mac_address ?? 'N/A',
            'networkConfigured' => $asset->network !== null,
            'dhcpEnabled' => (bool) ($asset->network?->dhcp_enabled ?? false),
            'domainIsAdmin' => $domainIsAdmin,
            'domainIsAcademic' => $domainIsAcademic,
            'domainIsNo' => $domainIsNo,
        ];
    }

    private function describeSecurityGuaya(mixed $value): string
    {
        if (is_bool($value) || $value === 0 || $value === 1) {
            return (bool) $value ? 'INSTALADA' : 'NO INSTALADA';
        }

        $normalized = $this->normalize((string) $value);
        if (in_array($normalized, ['SI', 'SÍ', 'TRUE', 'YES', '1'], true)) {
            return 'INSTALADA';
        }
        if (in_array($normalized, ['NO', 'FALSE', '0'], true)) {
            return 'NO INSTALADA';
        }

        return $this->joinParts([$value]);
    }

    private function joinLines(Collection $items, callable $format): string
    {
        $lines = $items
            ->map(fn ($item) => trim((string) $format($item)))
            ->filter(fn ($value) => $value !== '' && $value !== 'N/A')
            ->unique()
            ->values();

        return $lines->isNotEmpty() ? $lines->implode("\n") : 'N/A';
    }

    private function joinDistinct(Collection $items, callable $value): string
    {
        return $this->joinParts($items->map($value)->unique()->all());
    }

    private function joinParts(array $parts): string
    {
        $values = collect($parts)
            ->flatten()
            ->filter(fn ($part) => $part !== null && trim((string) $part) !== '')
            ->map(fn ($part) => trim((string) $part))
            ->unique()
            ->values();

        return $values->isNotEmpty() ? $values->implode(' · ') : 'N/A';
    }

    private function formatCapacity(float $capacity): string
    {
        return rtrim(rtrim(number_format($capacity, 1, '.', ''), '0'), '.');
    }

    private function normalize(string $value): string
    {
        return Str::upper(Str::ascii(trim($value)));
    }
}
