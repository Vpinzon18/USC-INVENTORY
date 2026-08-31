<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetApiController extends Controller
{
    /**
     * Procesa cambios diferenciales enviados por SIGMA Agent.
     */
    public function changes(Request $request)
    {
        try {
            Log::info('Cambios recibidos desde SIGMA Agent', [
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cambios recibidos correctamente.'
            ], 200);

        } catch (\Throwable $e) {

            Log::error(
                'Error procesando cambios de SIGMA Agent.',
                [
                    'message' => $e->getMessage()
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Error procesando cambios.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Configuración remota del agente.
     */
    public function config(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Configuración obtenida correctamente.',
        ]);
    }

    /**
     * Consulta si existe una nueva versión de SIGMA Agent.
     *
     * Esta versión es únicamente para pruebas.
     * Posteriormente la información saldrá de una tabla
     * de versiones administrada desde SIGMA.
     */
    public function update()
{
    return response()->json([
        'available' => true,
        'version' => '2.3',
        'downloadUrl' => asset('storage/updates/SigmaAgent-2.3.zip'),
        'mandatory' => false,
        'releaseNotes' =>
            'Prueba de actualización automática 2.2 a 2.3.'
    ]);
}

    /**
     * Procesa el inventario recibido desde SIGMA Agent.
     */
    public function report(Request $request)
    {
        try {

            Log::info(
                'JSON recibido desde SIGMA Agent',
                [
                    'data' => $request->all()
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Obtener información enviada por SIGMA Agent
            |--------------------------------------------------------------------------
            */

            $device = $request->input('device', []);
            $bios = $request->input('bios', []);
            $cpu = $request->input('cpu', []);
            $ram = $request->input('ram', []);
            $storage = $request->input('storage', []);
            $network = $request->input('network', []);
            $windows = $request->input('windows', []);
            $security = $request->input('security', []);
            $monitor = $request->input('monitor', []);
            $user = $request->input('user', []);
            $software = $request->input('software', []);

            /*
            |--------------------------------------------------------------------------
            | Sanitizar información
            |--------------------------------------------------------------------------
            */

            $device = $this->sanitizeArray($device);
            $bios = $this->sanitizeArray($bios);
            $cpu = $this->sanitizeArray($cpu);
            $ram = $this->sanitizeArray($ram);
            $storage = $this->sanitizeArray($storage);
            $network = $this->sanitizeArray($network);
            $windows = $this->sanitizeArray($windows);
            $security = $this->sanitizeArray($security);
            $monitor = $this->sanitizeArray($monitor);
            $user = $this->sanitizeArray($user);

            /*
            |--------------------------------------------------------------------------
            | Obtener MAC
            |--------------------------------------------------------------------------
            */

            $mac = $network['macAddress'] ?? null;

            if (!$mac) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'La dirección MAC es obligatoria.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Buscar o crear activo
            |--------------------------------------------------------------------------
            */

            $asset = Asset::firstOrNew([
                'mac_address' => $mac
            ]);

            /*
            |--------------------------------------------------------------------------
            | Información general del equipo
            |--------------------------------------------------------------------------
            */

            $asset->mac_address =
                $mac;

            $asset->serial_number =
                $device['serialNumber'] ?? null;

            $asset->hostname =
                $device['hostname'] ?? null;

            $asset->manufacturer =
                $device['manufacturer'] ?? null;

            $asset->model_version =
                $device['model'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | UUID
            |--------------------------------------------------------------------------
            |
            | Utilizamos hardware_uuid si este es el campo actual
            | de la tabla assets.
            |
            */

            $asset->hardware_uuid =
                $device['uuid'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | CPU
            |--------------------------------------------------------------------------
            |
            | Estos campos pertenecían anteriormente a assets.
            | Si ya migraste CPU a su propia tabla, NO debemos
            | guardar nuevamente esta información aquí.
            |
            */

            /*
            |--------------------------------------------------------------------------
            | RAM
            |--------------------------------------------------------------------------
            |
            | La RAM ahora se procesa mediante RamProcessor.
            | No se guarda nuevamente en assets.
            |
            */

            /*
            |--------------------------------------------------------------------------
            | STORAGE
            |--------------------------------------------------------------------------
            |
            | Si Storage también está separado mediante su processor,
            | no debemos duplicarlo en assets.
            |
            */

            /*
            |--------------------------------------------------------------------------
            | RED
            |--------------------------------------------------------------------------
            */

            $asset->ip_address =
                $network['ipv4'] ?? null;

            $asset->domain_name =
                $network['domain'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | WINDOWS
            |--------------------------------------------------------------------------
            */

            $asset->os_version =
                $windows['version'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | USUARIO
            |--------------------------------------------------------------------------
            */

            $asset->logged_user =
                $user['username'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | HASHES Y VERSIONES
            |--------------------------------------------------------------------------
            */

            $asset->hardware_hash =
                $request->input('hardwareHash');

            $asset->inventory_hash =
                $request->input('inventoryHash');

            $asset->agent_version =
                $request->input('agentVersion');

            $asset->inventory_version =
                $request->input('inventoryVersion');

            /*
            |--------------------------------------------------------------------------
            | ESTADO DEL AGENTE
            |--------------------------------------------------------------------------
            */

            $asset->last_seen_at =
                now();

            $asset->is_agent_managed =
                true;

            $asset->is_online =
                true;

            /*
            |--------------------------------------------------------------------------
            | Guardar activo
            |--------------------------------------------------------------------------
            */

            $asset->save();

            /*
            |--------------------------------------------------------------------------
            | SOFTWARE
            |--------------------------------------------------------------------------
            */

            if (
                $asset->id &&
                is_array($software)
            ) {

                $asset->software()->delete();

                foreach ($software as $program) {

                    if (
                        empty($program['name'])
                    ) {
                        continue;
                    }

                    $asset->software()->create([
                        'name' =>
                            strip_tags(
                                $program['name']
                            ),

                        'version' =>
                            strip_tags(
                                $program['version'] ?? ''
                            )
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Respuesta
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' =>
                    'Inventario recibido correctamente.',
                'asset_id' =>
                    $asset->id
            ], 200);

        } catch (\Throwable $e) {

            Log::error(
                'Error procesando inventario de SIGMA Agent.',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine()
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Error procesando inventario.',
                'error' =>
                    $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sanitiza cualquier arreglo recibido.
     */
    private function sanitizeArray(
        array $data
    ): array {

        $clean = [];

        foreach ($data as $key => $value) {

            if (is_array($value)) {

                $clean[$key] =
                    $this->sanitizeArray($value);

            } elseif (is_string($value)) {

                $clean[$key] =
                    strip_tags($value);

            } else {

                $clean[$key] =
                    $value;
            }
        }

        return $clean;
    }
}