<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetApiController extends Controller
{
    public function report(Request $request) 
    {
        // 1. Capturamos los datos crudos del agente
        $data = $request->json()->all();

        // 2. Guardamos o actualizamos el equipo
        // Usamos el serial_number como identificador único
        $asset = Asset::updateOrCreate(
            ['serial_number' => $data['serial_number'] ?? 'N/A'],
            [
                'hostname'      => $data['hostname'] ?? null,
                'cpu'           => $data['cpu'] ?? null,
                'ram'           => $data['ram'] ?? null,
                'storage'       => $data['storage'] ?? null,
                'mac_address'   => $data['mac_address'] ?? null,
                'wifi_card'     => $data['wifi_card'] ?? null,
                'graphics_card' => $data['graphics_card'] ?? null,
                'os_version'    => $data['os_version'] ?? null,
                'domain_name'   => $data['domain_name'] ?? null,
                'last_seen_at'  => now(),
                'ip_address'    => $request->ip()
            ]
        );

        // 3. Procesar Software asociado al equipo
        // Verificamos que el asset se haya creado/recuperado correctamente y que venga software
        if ($asset && isset($data['installed_software']) && is_array($data['installed_software'])) {
            
            // Log para debug (puedes ver esto en storage/logs/laravel.log)
            Log::info("Actualizando software para Asset ID: {$asset->id}");

            // Borramos los registros anteriores para refrescarlos totalmente
            // Esto asegura que si desinstalas algo en la PC, se borre de la BD
            $asset->software()->delete(); 
            
            // Creamos los nuevos registros de software
            foreach ($data['installed_software'] as $prog) {
                // Solo insertamos si el nombre es válido
                if (!empty($prog['name'])) {
                    $asset->software()->create([
                        'name'    => $prog['name'],
                        'version' => $prog['version'] ?? 'N/A'
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Reporte y software guardados correctamente',
            'asset_id' => $asset->id
        ], 200);
    }
}