<?php

namespace App\Services\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Asset;
use App\Services\AgentTokenAuthorizer;

use App\Services\Inventory\Processors\DeviceProcessor;
use App\Services\Inventory\Processors\NetworkProcessor;
use App\Services\Inventory\Processors\CpuProcessor;
use App\Services\Inventory\Processors\RamProcessor;
use App\Services\Inventory\Processors\StorageProcessor;
use App\Services\Inventory\Processors\WindowsProcessor;
use App\Services\Inventory\Processors\BiosProcessor;
use App\Services\Inventory\Processors\SecurityProcessor;
use App\Services\Inventory\Processors\UserProcessor;
use App\Services\Inventory\Processors\MonitorProcessor;
use App\Services\Inventory\Processors\SoftwareProcessor;
use App\Services\Inventory\Processors\BatteryProcessor;
use App\Services\Inventory\Processors\MotherboardProcessor;
use App\Services\Inventory\Processors\GpuProcessor;

class InventoryProcessor
{
    protected DeviceProcessor $deviceProcessor;
    protected NetworkProcessor $networkProcessor;
    protected CpuProcessor $cpuProcessor;
    protected RamProcessor $ramProcessor;
    protected StorageProcessor $storageProcessor;
    protected WindowsProcessor $windowsProcessor;
    protected BiosProcessor $biosProcessor;
    protected SecurityProcessor $securityProcessor;
    protected UserProcessor $userProcessor;
    protected MonitorProcessor $monitorProcessor;
    protected SoftwareProcessor $softwareProcessor;
    protected BatteryProcessor $batteryProcessor;
    protected MotherboardProcessor $motherboardProcessor;
    protected GpuProcessor $gpuProcessor;
    protected AgentTokenAuthorizer $tokenAuthorizer;

    public function __construct(
        DeviceProcessor $deviceProcessor,
        NetworkProcessor $networkProcessor,
        CpuProcessor $cpuProcessor,
        RamProcessor $ramProcessor,
        StorageProcessor $storageProcessor,
        WindowsProcessor $windowsProcessor,
        BiosProcessor $biosProcessor,
        SecurityProcessor $securityProcessor,
        UserProcessor $userProcessor,
        MonitorProcessor $monitorProcessor,
        SoftwareProcessor $softwareProcessor,
        BatteryProcessor $batteryProcessor,
        MotherboardProcessor $motherboardProcessor,
        GpuProcessor $gpuProcessor,
        AgentTokenAuthorizer $tokenAuthorizer,
    ) {
        $this->deviceProcessor = $deviceProcessor;
        $this->networkProcessor = $networkProcessor;
        $this->cpuProcessor = $cpuProcessor;
        $this->ramProcessor = $ramProcessor;
        $this->storageProcessor = $storageProcessor;
        $this->windowsProcessor = $windowsProcessor;
        $this->biosProcessor = $biosProcessor;
        $this->securityProcessor = $securityProcessor;
        $this->userProcessor = $userProcessor;
        $this->monitorProcessor = $monitorProcessor;
        $this->softwareProcessor = $softwareProcessor;
        $this->batteryProcessor = $batteryProcessor;
        $this->motherboardProcessor = $motherboardProcessor;
        $this->gpuProcessor = $gpuProcessor;
        $this->tokenAuthorizer = $tokenAuthorizer;
    }

    public function process(Request $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->processInventory($request->all(), $request);
        });
    }

    /**
     * Persist the complete inventory atomically so a processor failure
     * cannot leave only some of the device components updated.
     */
    protected function processInventory(array $inventory, Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | DEVICE
        |--------------------------------------------------------------------------
        */

        $asset = $this->deviceProcessor->process(
            $inventory['device'] ?? []
        );

        // Identity resolution and scope authorization share the transaction;
        // a token for another asset rolls the tentative write back.
        $this->tokenAuthorizer->authorizeAsset($request, $asset);

        /*
        |--------------------------------------------------------------------------
        | NETWORK
        |--------------------------------------------------------------------------
        */

        if (isset($inventory['network']) && is_array($inventory['network'])) {
            $asset = $this->networkProcessor->process($asset, $inventory['network']);
        }

        /*
        |--------------------------------------------------------------------------
        | CPU
        |--------------------------------------------------------------------------
        */

        if (isset($inventory['cpu']) && is_array($inventory['cpu'])) {
            if (!array_key_exists('collectionSucceeded', $inventory['cpu'])
                || $inventory['cpu']['collectionSucceeded'] === true) {
                $asset = $this->cpuProcessor->process($asset, $inventory['cpu']);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GPU
        |--------------------------------------------------------------------------
        */

        $gpus = $inventory['gpus'] ?? [];
        $gpuCollectionIsSafe = ($inventory['gpusCollectionSucceeded'] ?? false) === true;
        if ($gpus !== [] && $gpuCollectionIsSafe) {
            $asset = $this->gpuProcessor->process($asset, $gpus);
        }

        /*
        |--------------------------------------------------------------------------
        | RAM
        |--------------------------------------------------------------------------
        */

        $ramModules = $inventory['ram']['modules'] ?? null;
        $ramCollectionIsSafe = is_array($ramModules)
            && ($inventory['ram']['collectionSucceeded'] ?? false) === true;
        if ($ramCollectionIsSafe) {
            $asset = $this->ramProcessor->process($asset, $inventory['ram']['modules']);
        }

        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        $storageDevices = $inventory['storageDevices'] ?? [];
        if ($storageDevices === [] && $this->hasLegacyStorage($inventory['storage'] ?? null)) {
            $storageDevices = [$inventory['storage']];
        }
        $storageCollectionIsSafe = ($inventory['storageCollectionSucceeded'] ?? false) === true;
        if ($storageDevices !== [] && $storageCollectionIsSafe) {
            $asset = $this->storageProcessor->process($asset, $storageDevices);
        }

        /*
        |--------------------------------------------------------------------------
        | WINDOWS
        |--------------------------------------------------------------------------
        */

        if (isset($inventory['windows']) && is_array($inventory['windows'])) {
            $asset = $this->windowsProcessor->process($asset, $inventory['windows']);
        }

        /*
        |--------------------------------------------------------------------------
        | BIOS
        |--------------------------------------------------------------------------
        */

        $asset = $this->biosProcessor->process(
            $asset,
            $inventory['bios'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        if (isset($inventory['security']) && is_array($inventory['security'])) {
            $asset = $this->securityProcessor->process($asset, $inventory['security']);
        }

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $asset = $this->userProcessor->process(
            $asset,
            $inventory['user'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | MONITORS
        |--------------------------------------------------------------------------
        */

        $monitors = $inventory['monitors'] ?? [];
        if ($monitors === [] && $this->hasLegacyMonitor($inventory['monitor'] ?? null)) {
            $monitors = [$inventory['monitor']];
        }
        if ($monitors !== []) {
            $asset = $this->monitorProcessor->process($asset, $monitors);
        }

        /*
        |--------------------------------------------------------------------------
        | SOFTWARE
        |--------------------------------------------------------------------------
        */

        $softwareCollectionIsSafe = ($inventory['softwareCollectionSucceeded'] ?? false) === true;
        if (isset($inventory['software']) && is_array($inventory['software'])) {
            $asset = $this->softwareProcessor->process(
                $asset,
                $inventory,
                $softwareCollectionIsSafe
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RUSTDESK
        |--------------------------------------------------------------------------
        | The client is optional. If its ID is absent, preserve any ID already
        | registered on the asset; never clear it because RustDesk is unavailable.
        */

        $rustDeskId = trim((string) ($inventory['rustDeskId'] ?? ''));
        if ($rustDeskId !== '') {
            $duplicate = Asset::query()
                ->where('rustdesk_id', $rustDeskId)
                ->where('id', '!=', $asset->getKey())
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'rustDeskId' => 'Este ID de RustDesk ya está asociado a otro equipo.',
                ]);
            }

            $asset->update(['rustdesk_id' => $rustDeskId]);
        }

        /*
        |--------------------------------------------------------------------------
        | BATTERY
        |--------------------------------------------------------------------------
        */

        $asset = $this->batteryProcessor->process(
            $asset,
            $inventory['battery'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | MOTHERBOARD
        |--------------------------------------------------------------------------
        */

        $asset = $this->motherboardProcessor->process(
            $asset,
            $inventory['motherboard'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | METADATOS DEL INVENTARIO
        |--------------------------------------------------------------------------
        |
        | Estos datos pertenecen al inventario enviado por el Agent,
        | pero no corresponden a ningún componente específico.
        |
        */

        $metadata = [
            'hardware_hash' =>
                $inventory['hardwareHash'] ?? null,

            'inventory_hash' =>
                $inventory['inventoryHash'] ?? null,

            'agent_version' =>
                $inventory['agentVersion'] ?? null,

            'inventory_version' =>
                $inventory['inventoryVersion'] ?? null,
        ];

        $metadata = array_filter(
            $metadata,
            static fn ($value) => $value !== null
        );

        if (!empty($metadata)) {
            $asset->update($metadata);
        }

        /*
        |--------------------------------------------------------------------------
        | ESTADO DEL SIGMA AGENT
        |--------------------------------------------------------------------------
        */

        $asset->update([
            'is_agent_managed' => true,
            'agent_last_seen' => now(),
            'last_inventory_at' => now(),
            'is_online' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'asset_id' => $asset->id,
            'agent_id' => $asset->agent_uuid,
        ]);
    }

    private function hasLegacyStorage(mixed $storage): bool
    {
        if (!is_array($storage)) {
            return false;
        }

        return filled($storage['model'] ?? null)
            || filled($storage['serial'] ?? null)
            || (int) ($storage['capacityGB'] ?? $storage['capacityGb'] ?? 0) > 0;
    }

    private function hasLegacyMonitor(mixed $monitor): bool
    {
        if (!is_array($monitor)) {
            return false;
        }

        return filled($monitor['serial'] ?? null)
            || filled($monitor['model'] ?? null)
            || filled($monitor['brand'] ?? null);
    }
}
