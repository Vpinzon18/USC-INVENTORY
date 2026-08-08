<?php

namespace App\Services\Inventory;

use Illuminate\Http\Request;
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
use App\Services\Inventory\Processors\HistoryProcessor;
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
    protected HistoryProcessor $historyProcessor;
    protected BatteryProcessor $batteryProcessor;
    protected MotherboardProcessor $motherboardprocessor;
    protected GpuProcessor $gpuProcessor;

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
        HistoryProcessor $historyProcessor,
        BatteryProcessor $batteryProcessor,
        MotherboardProcessor $motherboardprocessor,
        GpuProcessor $gpuProcessor,
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
        $this->historyProcessor = $historyProcessor;
        $this->batteryProcessor = $batteryProcessor;
        $this->motherboardprocessor = $motherboardprocessor;
        $this->gpuProcessor = $gpuProcessor;
    }

    public function process(Request $request)
    {
        $inventory = $request->all();

        // Crear u obtener el activo
        $asset = $this->deviceProcessor->process(
            $inventory['device']
        );

        // Network
        $asset = $this->networkProcessor->process(
            $asset,
            $inventory['network']
        );

        // CPU
        $asset = $this->cpuProcessor->process(
            $asset,
            $inventory['cpu']
        );

        // GPU
        $asset = $this->gpuProcessor->process(
            $asset,
            $inventory['gpus'] ?? []
        );

        $asset = $this->ramProcessor->process(
            $asset,
            $inventory['ram']['modules'] ?? []
        );

        // Storage
        $asset = $this->storageProcessor->process(
            $asset,
            $inventory['storageDevices'] ?? []
        );

        // Windows
        $asset = $this->windowsProcessor->process(
            $asset,
            $inventory['windows']
        );

        // BIOS
        $asset = $this->biosProcessor->process(
            $asset,
            $inventory['bios']
        );

        // Security
        $asset = $this->securityProcessor->process(
            $asset,
            $inventory['security']
        );

        // User
        $asset = $this->userProcessor->process(
            $asset,
            $inventory['user']
        );

        // Monitors
        $asset = $this->monitorProcessor->process(
            $asset,
            $inventory['monitors'] ?? []
        );

        // Software
        $asset = $this->softwareProcessor->process(
            $asset,
            $inventory
        );

        // Battery
        $asset = $this->batteryProcessor->process(
            $asset,
            $inventory['battery'] ?? []
        );

        // Motherboard
        $asset = $this->motherboardprocessor->process(
            $asset,
            $inventory['motherboard'] ?? []
        );

        return response()->json([
            'success'  => true,
            'asset_id' => $asset->id,
        ]);
    }
}
