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
    try {
        $data = $request->json()->all();
        
        $mac = $data['mac_address'] ?? 'N/A';
        $asset = Asset::firstOrNew(['mac_address' => $mac]);
        
        // 1. Datos básicos
        $asset->serial_number = $data['serial_number'] ?? 'N/A';
        $asset->hostname      = $data['hostname'] ?? null;
        $asset->ram           = $data['ram'] ?? null;
        $asset->os_version    = $data['os_version'] ?? null;
        $asset->domain_name   = $data['domain_name'] ?? null;
        $asset->last_seen_at  = now();
        $asset->ip_address    = $request->ip();
        
        // 2. Hardware Desglosado (Mapeo de objetos HardwareInfo)
        // CPU
        $asset->cpu_brand     = $data['cpu']['brand'] ?? null;
        $asset->cpu_model     = $data['cpu']['name'] ?? null;
        
        // Almacenamiento (Como ya tienes columnas marca/modelo, úsalas)
        $asset->storage_brand = $data['storage']['brand'] ?? null;
        $asset->storage_model = $data['storage']['name'] ?? null;
        
        // Motherboard
        $asset->board_brand   = $data['motherboard']['brand'] ?? null;
        $asset->board_model   = $data['motherboard']['name'] ?? null;
        
        // Gráficos
        $asset->gpu_brand     = $data['graphics_card']['brand'] ?? null;
        $asset->gpu_model     = $data['graphics_card']['name'] ?? null;
        
        // Wi-Fi
        $asset->wifi_brand    = $data['wifi_card']['brand'] ?? null;
        $asset->wifi_model    = $data['wifi_card']['name'] ?? null;
        
$asset->ram_brand = $request->input('ram_brand');
    $asset->ram_model = $request->input('ram_model');
    $asset->ram_capacity_gb = (int)$request->input('ram_capacity_gb');

        $asset->save(); 

        // 3. Procesar Software (Limpieza)
        if ($asset->id && isset($data['installed_software']) && is_array($data['installed_software'])) {
            // Borramos solo el software asociado a este asset antes de volver a insertar
            $asset->software()->delete(); 
            
            foreach ($data['installed_software'] as $prog) {
                if (!empty($prog['name'])) {
                    $asset->software()->create([
                        'name'    => $prog['name'],
                        'version' => $prog['version'] ?? 'N/A'
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Guardado exitoso', 'id' => $asset->id], 200);

    } catch (\Exception $e) {
        Log::error('ERROR EN API: ' . $e->getMessage());
        return response()->json(['error' => 'Error procesando el inventario'], 500);
    }
}
}