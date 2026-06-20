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
        // 1. LA ADUANA (Validación estricta de estructura y longitud)
        // Usamos la notación de punto (ej. cpu.brand) para validar JSONs anidados
        $validated = $request->validate([
            'mac_address'             => 'required|string|max:50',
            'serial_number'           => 'nullable|string|max:100',
            'hostname'                => 'nullable|string|max:100',
            'ram'                     => 'nullable|string|max:50',
            'os_version'              => 'nullable|string|max:100',
            'domain_name'             => 'nullable|string|max:100',
            'model_version'           => 'nullable|string|max:100',
            
            // Validación de arreglos anidados
            'cpu.brand'               => 'nullable|string|max:100',
            'cpu.name'                => 'nullable|string|max:100',
            'storage.brand'           => 'nullable|string|max:100',
            'storage.name'            => 'nullable|string|max:100',
            'motherboard.brand'       => 'nullable|string|max:100',
            'motherboard.name'        => 'nullable|string|max:100',
            'graphics_card.brand'     => 'nullable|string|max:100',
            'graphics_card.name'      => 'nullable|string|max:100',
            'wifi_card.brand'         => 'nullable|string|max:100',
            'wifi_card.name'          => 'nullable|string|max:100',
            
            'ram_brand'               => 'nullable|string|max:100',
            'ram_model'               => 'nullable|string|max:100',
            'ram_capacity_gb'         => 'nullable|integer',
            
            // Validación del arreglo de software
            'installed_software'      => 'nullable|array',
            'installed_software.*.name'    => 'required_with:installed_software|string|max:200',
            'installed_software.*.version' => 'nullable|string|max:100',
        ]);

        try {
            // 2. EL COLADOR ANTI-XSS (Sanitización recursiva del JSON)
            // Esto arranca cualquier etiqueta HTML de todos los valores del JSON recibido
            $cleanData = $this->sanitizeArray($validated);

            $mac = $cleanData['mac_address'];
            $asset = Asset::firstOrNew(['mac_address' => $mac]);

            // 3. ASIGNACIÓN SEGURA
            $asset->serial_number = $cleanData['serial_number'] ?? 'N/A';
            $asset->hostname      = $cleanData['hostname'] ?? null;
            $asset->ram           = $cleanData['ram'] ?? null;
            $asset->os_version    = $cleanData['os_version'] ?? null;
            $asset->domain_name   = $cleanData['domain_name'] ?? null;
            $asset->model_version = $cleanData['model_version'] ?? null;
            $asset->last_seen_at  = now();
            $asset->ip_address    = $request->ip();

            // Hardware Desglosado
            $asset->cpu_brand     = $cleanData['cpu']['brand'] ?? null;
            $asset->cpu_model     = $cleanData['cpu']['name'] ?? null;
            $asset->storage_brand = $cleanData['storage']['brand'] ?? null;
            $asset->storage_model = $cleanData['storage']['name'] ?? null;
            $asset->board_brand   = $cleanData['motherboard']['brand'] ?? null;
            $asset->board_model   = $cleanData['motherboard']['name'] ?? null;
            $asset->gpu_brand     = $cleanData['graphics_card']['brand'] ?? null;
            $asset->gpu_model     = $cleanData['graphics_card']['name'] ?? null;
            $asset->wifi_brand    = $cleanData['wifi_card']['brand'] ?? null;
            $asset->wifi_model    = $cleanData['wifi_card']['name'] ?? null;

            $asset->ram_brand       = $cleanData['ram_brand'] ?? null;
            $asset->ram_model       = $cleanData['ram_model'] ?? null;
            $asset->ram_capacity_gb = $cleanData['ram_capacity_gb'] ?? null;
            $asset->is_agent_managed = true;

            $asset->save();

            // 4. PROCESAR SOFTWARE SEGÚRO
            if ($asset->id && !empty($cleanData['installed_software'])) {
                $asset->software()->delete();

                foreach ($cleanData['installed_software'] as $prog) {
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

    /**
     * Función recursiva para limpiar todos los textos de un array multidimensional (JSON)
     */
    private function sanitizeArray(array $data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Si es un arreglo (como 'cpu' o 'installed_software'), se limpia por dentro
                $clean[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                // Si es texto libre, le aplicamos el colador
                $clean[$key] = strip_tags($value);
            } else {
                // Si es número, booleano o nulo, pasa directo
                $clean[$key] = $value;
            }
        }
        return $clean;
    }
}