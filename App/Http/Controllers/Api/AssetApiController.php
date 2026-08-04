<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetApiController extends Controller
{
    public function report(Request $request)
    {
        Log::info($request->all());

return response()->json($request->all());
        try {
            Log::info('JSON recibido desde SIGMA Agent');
        Log::info($request->all());

        return response()->json($request->all());

            /*
            |--------------------------------------------------------------------------
            | Obtener objetos enviados por SIGMA Agent
            |--------------------------------------------------------------------------
            */

            $device   = $request->input('device', []);
            $bios     = $request->input('bios', []);
            $cpu      = $request->input('cpu', []);
            $ram      = $request->input('ram', []);
            $storage  = $request->input('storage', []);
            $network  = $request->input('network', []);
            $windows  = $request->input('windows', []);
            $security = $request->input('security', []);
            $monitor  = $request->input('monitor', []);
            $user     = $request->input('user', []);
            $software = $request->input('software', []);

            /*
            |--------------------------------------------------------------------------
            | Validar MAC
            |--------------------------------------------------------------------------
            */

            $mac = $network['macAddress'] ?? null;

            if (!$mac) {
                return response()->json([
                    'message' => 'La dirección MAC es obligatoria.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Sanitizar información
            |--------------------------------------------------------------------------
            */

            $device   = $this->sanitizeArray($device);
            $bios     = $this->sanitizeArray($bios);
            $cpu      = $this->sanitizeArray($cpu);
            $ram      = $this->sanitizeArray($ram);
            $storage  = $this->sanitizeArray($storage);
            $network  = $this->sanitizeArray($network);
            $windows  = $this->sanitizeArray($windows);
            $security = $this->sanitizeArray($security);
            $monitor  = $this->sanitizeArray($monitor);
            $user     = $this->sanitizeArray($user);

            /*
            |--------------------------------------------------------------------------
            | Buscar activo
            |--------------------------------------------------------------------------
            */

            $asset = Asset::firstOrNew([
                'mac_address' => $mac
            ]);

            /*
            |--------------------------------------------------------------------------
            | Información general
            |--------------------------------------------------------------------------
            */

            $asset->mac_address = $mac;

            $asset->serial_number = $device['serialNumber'] ?? null;

            $asset->hostname = $device['hostname'] ?? null;

            $asset->manufacturer = $device['manufacturer'] ?? null;

            $asset->model_version = $device['model'] ?? null;

            $asset->uuid = $device['uuid'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | CPU
            |--------------------------------------------------------------------------
            */

            $asset->cpu_brand = $cpu['brand'] ?? null;

            $asset->cpu_model = $cpu['model'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | RAM
            |--------------------------------------------------------------------------
            */

            $asset->ram = isset($ram['capacityGB'])
                ? $ram['capacityGB'] . ' GB'
                : null;

            /*
            |--------------------------------------------------------------------------
            | Disco
            |--------------------------------------------------------------------------
            */

            $asset->storage_model = $storage['model'] ?? null;

            $asset->storage_serial = $storage['serial'] ?? null;

            if (isset($storage['capacityGB'])) {
                $asset->storage_capacity = $storage['capacityGB'];
            }

            /*
            |--------------------------------------------------------------------------
            | Red
            |--------------------------------------------------------------------------
            */

            $asset->ip_address = $network['ipv4'] ?? null;

            $asset->domain_name = $network['domain'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | Windows
            |--------------------------------------------------------------------------
            */

            $asset->os_version = $windows['version'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | Usuario
            |--------------------------------------------------------------------------
            */

            $asset->logged_user = $user['username'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | Monitor
            |--------------------------------------------------------------------------
            */

            $asset->monitor_serial = $monitor['serial'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | Hashes
            |--------------------------------------------------------------------------
            */

            $asset->hardware_hash = $request->input('hardwareHash');

            $asset->inventory_hash = $request->input('inventoryHash');

            $asset->agent_version = $request->input('agentVersion');

            /*
            |--------------------------------------------------------------------------
            | Estado
            |--------------------------------------------------------------------------
            */

            $asset->last_seen_at = now();

            $asset->is_agent_managed = true;

            $asset->save();

            /*
            |--------------------------------------------------------------------------
            | Software
            |--------------------------------------------------------------------------
            */

            if ($asset->id && is_array($software)) {

                $asset->software()->delete();

                foreach ($software as $program) {

                    if (empty($program['name'])) {
                        continue;
                    }

                    $asset->software()->create([
                        'name' => strip_tags($program['name']),
                        'version' => strip_tags($program['version'] ?? '')
                    ]);
                }
            }

            return response()->json([
                'message' => 'Inventario recibido correctamente.',
                'id' => $asset->id
            ], 200);

        } catch (\Throwable $e) {

            Log::error($e);

            return response()->json([
                'message' => 'Error procesando inventario.',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Sanitiza cualquier arreglo recibido.
     */
    private function sanitizeArray(array $data): array
    {
        $clean = [];

        foreach ($data as $key => $value) {

            if (is_array($value)) {

                $clean[$key] = $this->sanitizeArray($value);

            } elseif (is_string($value)) {

                $clean[$key] = strip_tags($value);

            } else {

                $clean[$key] = $value;

            }

        }

        return $clean;
    }
}