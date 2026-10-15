<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DeviceProcessor
{
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

            'model_version' => $device['model'] ?? null,

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
