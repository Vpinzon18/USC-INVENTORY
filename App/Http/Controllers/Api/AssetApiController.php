<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetApiController extends Controller
{
    public function __construct(
        protected InventoryProcessor $inventoryProcessor
    ) {
    }

    /**
     * Procesa cambios diferenciales enviados por SIGMA Agent.
     */
    public function changes(Request $request)
    {
        try {

            Log::info(
                'Cambios recibidos desde SIGMA Agent',
                [
                    'data' => $request->all()
                ]
            );

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
     */
    public function update()
    {
        return response()->json([
            'available' => true,
            'version' => '2.3',
            'downloadUrl' => asset(
                'storage/updates/SigmaAgent-2.3.zip'
            ),
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

            /*
            |--------------------------------------------------------------------------
            | Registrar inventario recibido
            |--------------------------------------------------------------------------
            */

            Log::info(
                'JSON recibido desde SIGMA Agent',
                [
                    'data' => $request->all()
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Validación básica
            |--------------------------------------------------------------------------
            |
            | El Agent debe enviar como mínimo la información del dispositivo.
            |
            */

            if (
                !$request->has('device') ||
                !is_array($request->input('device'))
            ) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'La información del dispositivo es obligatoria.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Procesar inventario
            |--------------------------------------------------------------------------
            |
            | Toda la distribución de información queda delegada a:
            |
            | InventoryProcessor
            |
            | ├── DeviceProcessor
            | ├── NetworkProcessor
            | ├── CpuProcessor
            | ├── RamProcessor
            | ├── StorageProcessor
            | ├── WindowsProcessor
            | ├── BiosProcessor
            | ├── SecurityProcessor
            | ├── UserProcessor
            | ├── MonitorProcessor
            | ├── SoftwareProcessor
            | ├── BatteryProcessor
            | ├── MotherboardProcessor
            | └── GpuProcessor
            |
            */

            $response = $this->inventoryProcessor->process(
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | Respuesta
            |--------------------------------------------------------------------------
            */

            return $response;

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
}