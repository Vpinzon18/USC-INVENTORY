<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class DeviceProcessor
{
    public function process(array $device): Asset
    {
        /*
        |--------------------------------------------------------------------------
        | Identificación del equipo
        |--------------------------------------------------------------------------
        |
        | El serial_number es actualmente el identificador utilizado
        | para localizar el Asset existente.
        |
        */

        $serialNumber = $device['serialNumber'] ?? null;

        if (empty($serialNumber)) {
            throw new \InvalidArgumentException(
                'El inventario no contiene un número de serie válido.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Información principal del equipo
        |--------------------------------------------------------------------------
        */

        $values = [

            'hostname' => $device['hostname'] ?? null,

            'model_version' => $device['model'] ?? null,

            'manufacturer' => $device['manufacturer'] ?? null,

            'hardware_uuid' => $device['uuid'] ?? null,

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

        return Asset::updateOrCreate(
            [
                'serial_number' => $serialNumber,
            ],
            $values
        );
    }
}