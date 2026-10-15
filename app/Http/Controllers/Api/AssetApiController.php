<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        return response()->json([
            'success' => false,
            'message' => 'La sincronización diferencial no está implementada. Envíe un inventario completo.',
        ], 501);
    }

    /**
     * Configuración remota del agente.
     */
    public function config(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'La configuración remota del agente no está implementada.',
        ], 501);
    }

    /**
     * Consulta si existe una nueva versión de SIGMA Agent.
     *
     * Esta versión es únicamente para pruebas.
     */
    public function update()
    {
        $appUrlParts = parse_url((string) config('app.url'));
        if (!is_array($appUrlParts) ||
            strtolower((string) ($appUrlParts['scheme'] ?? '')) !== 'https' ||
            empty($appUrlParts['host'])) {
            return response()->json([
                'available' => false,
                'message' => 'La publicación de actualizaciones requiere APP_URL HTTPS.',
            ], 503);
        }

        $version = (string) config('sigma_agent_updates.version');
        $packageName = basename((string) config('sigma_agent_updates.package'));
        $expectedHash = strtolower((string) config('sigma_agent_updates.sha256'));
        $releaseNotes = (string) config('sigma_agent_updates.release_notes');
        $mandatory = (bool) config('sigma_agent_updates.mandatory', false);
        $packageDirectory = storage_path('app/agent-updates');
        $packagePath = $packageDirectory . DIRECTORY_SEPARATOR . $packageName;

        if ($version === '' || $packageName === '' ||
            !preg_match('/^[a-f0-9]{64}$/', $expectedHash) ||
            !is_file($packagePath)) {
            return response()->json([
                'available' => false,
                'message' => 'No hay una actualización válida publicada.',
            ]);
        }

        $actualHash = Cache::remember(
            'sigma-agent-update-hash:' . sha1($packagePath . '|' . filemtime($packagePath) . '|' . filesize($packagePath)),
            now()->addMinutes(10),
            fn () => hash_file('sha256', $packagePath)
        );

        if (!is_string($actualHash) || !hash_equals($expectedHash, strtolower($actualHash))) {
            Log::error('El SHA-256 configurado para la actualización Sigma no coincide con el paquete.', [
                'version' => $version,
                'package' => $packageName,
            ]);

            return response()->json([
                'available' => false,
                'message' => 'El paquete publicado no superó la validación de integridad.',
            ], 503);
        }

        $downloadPath = parse_url(
            route('api.v1.agent.update.download', ['version' => $version]),
            PHP_URL_PATH
        );
        $downloadHost = $appUrlParts['host'];
        if (isset($appUrlParts['port']) && $appUrlParts['port'] !== 443) {
            $downloadHost .= ':' . $appUrlParts['port'];
        }

        return response()->json([
            'available' => true,
            'version' => $version,
            'downloadUrl' => 'https://' . $downloadHost . $downloadPath,
            'sha256' => $expectedHash,
            'mandatory' => $mandatory,
            'releaseNotes' => $releaseNotes,
        ]);
    }

    /** Descarga el artefacto desde almacenamiento privado, con autenticación Sanctum. */
    public function downloadUpdate(string $version)
    {
        abort_unless(hash_equals((string) config('sigma_agent_updates.version'), $version), 404);

        $packageName = basename((string) config('sigma_agent_updates.package'));
        $packagePath = storage_path('app/agent-updates' . DIRECTORY_SEPARATOR . $packageName);
        $expectedHash = strtolower((string) config('sigma_agent_updates.sha256'));

        abort_unless($packageName !== '' && is_file($packagePath), 404);

        $actualHash = hash_file('sha256', $packagePath);
        abort_unless(is_string($actualHash) && hash_equals($expectedHash, strtolower($actualHash)), 503);

        return response()->download($packagePath, $packageName, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
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
                    'user_id' => $request->user()?->getAuthIdentifier(),
                    'fields' => array_keys($request->all())
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

                    'user_id' => $request->user()?->getAuthIdentifier()
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Error procesando inventario.',
                'error' => 'No fue posible procesar la solicitud.'
            ], 500);
        }
    }
}
