<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class MonitorProcessor
{
    public function process(Asset $asset, array $monitors): Asset
    {
        foreach ($monitors as $monitor) {

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICADOR DEL MONITOR
            |--------------------------------------------------------------------------
            */

            $serialNumber = $monitor['serial'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | VALIDAR SERIAL
            |--------------------------------------------------------------------------
            |
            | IMPORTANTE:
            | "0" es un valor que PHP considera empty(),
            | pero en nuestro caso el collector lo está enviando
            | como serial del monitor AUO.
            |--------------------------------------------------------------------------
            */

            if (
                $serialNumber === null ||
                trim((string) $serialNumber) === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR O CREAR MONITOR
            |--------------------------------------------------------------------------
            */

            $asset->monitors()->updateOrCreate(
                [
                    'serial_number' => $serialNumber,
                ],
                [
                    'brand' => $monitor['brand'] ?? null,

                    'model' => $monitor['model'] ?? null,

                    'manufacturer_code' =>
                        $monitor['manufacturerCode'] ?? null,

                    'year' => $monitor['year'] ?? null,

                    'size' => $monitor['size'] ?? null,

                    'resolution' =>
                        $monitor['resolution'] ?? null,

                    'refresh_rate' =>
                        $monitor['refreshRate'] ?? null,
                ]
            );
        }

        return $asset;
    }
}