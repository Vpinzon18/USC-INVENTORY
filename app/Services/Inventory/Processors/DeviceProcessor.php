<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;
use App\Services\Inventory\DeviceModelResolver;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DeviceProcessor
{
    public function __construct(private readonly DeviceModelResolver $modelResolver)
    {
    }

    /**
     * Rebind a reinstalled agent to an existing asset after explicit admin approval.
     * The asset must be identified by a hardware UUID or a unique serial number;
     * hostname-only matching is deliberately not accepted for credential recovery.
     */
    public function processForReenrollment(array $device): Asset
    {
        $agentUuid = isset($device['agentId']) ? strtolower(trim((string) $device['agentId'])) : null;
        $hardwareUuid = isset($device['uuid']) ? strtolower(trim((string) $device['uuid'])) : null;
        $serialNumber = isset($device['serialNumber']) ? trim((string) $device['serialNumber']) : null;
        $serialNumber = $serialNumber === '' ? null : $serialNumber;

        if (!$agentUuid || !\Illuminate\Support\Str::isUuid($agentUuid)) {
            throw ValidationException::withMessages([
                'device.agentId' => 'La solicitud no contiene un identificador válido del agente.',
            ]);
        }

        $byHardwareUuid = $hardwareUuid
            ? Asset::query()->where('hardware_uuid', $hardwareUuid)->first()
            : null;

        $serialMatches = $serialNumber
            ? Asset::query()->where('serial_number', $serialNumber)->limit(2)->get()
            : collect();

        if ($serialMatches->count() > 1) {
            throw ValidationException::withMessages([
                'device.serialNumber' => 'El serial coincide con varios activos; no se puede recuperar el agente automáticamente.',
            ]);
        }

        $bySerial = $serialMatches->first();
        if ($byHardwareUuid && $bySerial && $byHardwareUuid->isNot($bySerial)) {
            throw ValidationException::withMessages([
                'device' => 'El UUID de hardware y el serial identifican activos distintos. Verifique el equipo antes de continuar.',
            ]);
        }

        $asset = $byHardwareUuid ?? $bySerial;
        if (!$asset) {
            throw ValidationException::withMessages([
                'device' => 'No se encontró un activo existente por UUID de hardware o serial único. No se creará un activo nuevo durante la recuperación.',
            ]);
        }

        $agentOwner = Asset::query()->where('agent_uuid', $agentUuid)->first();
        if ($agentOwner && $agentOwner->isNot($asset)) {
            throw ValidationException::withMessages([
                'device.agentId' => 'El identificador nuevo del agente ya pertenece a otro activo.',
            ]);
        }

        // Reuse normal inventory updates without triggering the normal immutable-ID guard.
        $update = $device;
        unset($update['agentId']);
        $asset = $this->process($update + ['assetId' => $asset->getKey()]);
        $asset->agent_uuid = $agentUuid;
        $asset->save();

        return $asset;
    }

    public function process(array $device): Asset
    {
        /*
        |--------------------------------------------------------------------------
        | Identificación del equipo
        |--------------------------------------------------------------------------
        |
        | La identidad preferida es el ID interno devuelto por el servidor.
        | Para el primer contacto se admiten el UUID de hardware o un serial
        | que coincida con un único activo existente.
        |
        */

        $serialNumber = isset($device['serialNumber'])
            ? trim((string) $device['serialNumber'])
            : null;
        $serialNumber = $serialNumber === '' ? null : $serialNumber;
        $hostname = isset($device['hostname'])
            ? trim((string) $device['hostname'])
            : null;
        $hostname = $hostname === '' ? null : $hostname;
        $assetId = $device['assetId'] ?? null;
        $agentUuid = isset($device['agentId'])
            ? strtolower((string) $device['agentId'])
            : null;
        $hardwareUuid = isset($device['uuid'])
            ? strtolower((string) $device['uuid'])
            : null;

        $asset = $assetId ? Asset::find($assetId) : null;

        if ($agentUuid !== null) {
            $byAgentUuid = Asset::where('agent_uuid', $agentUuid)->first();
            if ($asset && $byAgentUuid && $asset->isNot($byAgentUuid)) {
                throw ValidationException::withMessages([
                    'device.agentId' => 'El identificador del agente pertenece a otro activo.',
                ]);
            }
            $asset ??= $byAgentUuid;
        }

        if ($hardwareUuid !== null) {
            $byHardwareUuid = Asset::where('hardware_uuid', $hardwareUuid)->first();
            if ($asset && $byHardwareUuid && $asset->isNot($byHardwareUuid)) {
                throw ValidationException::withMessages([
                    'device.uuid' => 'El UUID de hardware ya está vinculado a otro activo.',
                ]);
            }
            $asset ??= $byHardwareUuid;
        }

        $ambiguousSerial = false;
        if (!$asset && $serialNumber !== null) {
            $matches = Asset::where('serial_number', $serialNumber)
                ->limit(2)
                ->get();

            if ($matches->count() === 1) {
                $asset = $matches->first();
            } else {
                $ambiguousSerial = $matches->count() > 1;
            }
        }

        // Transitional bootstrap for existing assets whose hardware UUID was
        // not persisted by older SIGMA Agent versions.
        if (!$asset && $hostname !== null) {
            $hostnameMatches = Asset::where('hostname', $hostname)
                ->limit(2)
                ->get();

            if ($hostnameMatches->count() === 1) {
                $asset = $hostnameMatches->first();
            }
        }

        if ($ambiguousSerial && !$asset && $agentUuid === null && $hardwareUuid === null) {
            throw ValidationException::withMessages([
                'device.serialNumber' => 'El serial está duplicado. El agente debe enviar assetId, agentId, UUID de hardware o un hostname único.',
            ]);
        }

        if (!$asset && $serialNumber === null && $agentUuid === null && $hardwareUuid === null) {
            throw ValidationException::withMessages([
                'device' => 'Se requiere assetId, agentId o UUID de hardware cuando el serial no está disponible.',
            ]);
        }

        if ($asset && $agentUuid !== null && $asset->agent_uuid !== null && $asset->agent_uuid !== $agentUuid) {
            throw ValidationException::withMessages([
                'device.agentId' => 'El agente ya está vinculado a otro identificador estable.',
            ]);
        }

        $agentUuid ??= $asset?->agent_uuid;
        $agentUuid ??= (string) Str::uuid();

        /*
        |--------------------------------------------------------------------------
        | Información principal del equipo
        |--------------------------------------------------------------------------
        */

        $values = [

            'hostname' => $device['hostname'] ?? null,

            'model_version' => $this->modelResolver->resolve(
                $device['manufacturer'] ?? null,
                $device['model'] ?? null
            ),

            'manufacturer' => $device['manufacturer'] ?? null,

            'hardware_uuid' => $hardwareUuid,
            'agent_uuid' => $agentUuid,

            'last_seen_at' => now(),

            'is_agent_managed' => true,
        ];

        /*
        |--------------------------------------------------------------------------
        | Eliminar únicamente valores nulos
        |--------------------------------------------------------------------------
        */

        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        /*
        |--------------------------------------------------------------------------
        | Crear o actualizar Asset
        |--------------------------------------------------------------------------
        */

        $asset ??= new Asset();
        $asset->fill($values);

        if ($serialNumber !== null) {
            $asset->serial_number = $serialNumber;
        }

        if (empty($asset->hostname)) {
            $asset->hostname = 'SIGMA-' . strtoupper(substr($agentUuid, 0, 8));
        }

        $asset->save();

        return $asset;
    }
}
