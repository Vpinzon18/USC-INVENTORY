<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Campus;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon; 

class InventoryController extends Controller
{
   public function index()
{
    $assets = \App\Models\Asset::with(['room.building.campus', 'software'])->latest()->get();
    
    $total = $assets->count();
    $online = $assets->filter(function ($asset) {
        return $asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->gt(now()->subMinutes(10));
    })->count();
    
    $totalUsers = \App\Models\User::count();
    $totalCampuses = \App\Models\Campus::count();
    $totalRooms = \App\Models\Room::count();

    $modelStats = \Illuminate\Support\Facades\DB::table('assets')
        ->select('model_version', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('model_version')
        ->groupBy('model_version')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();

    $mostUsedModel = $modelStats->first();

    return view('dashboard', compact(
        'assets', 
        'total', 
        'online', 
        'totalUsers', 
        'totalCampuses', 
        'totalRooms', 
        'modelStats', 
        'mostUsedModel'
    ));
}
   public function report(Request $request)
    { 
        // 1. LA ADUANA (Validar estructura y longitud)
        $validated = $request->validate([
            'serial_number' => 'required|string|max:100',
            'hostname'      => 'nullable|string|max:100',
            'ip_address'    => 'nullable|ip', // Validamos que sea una IP real
            'cpu'           => 'nullable|string|max:100',
            'ram'           => 'nullable|string|max:50',
            'storage'       => 'nullable|string|max:100',
            'model_version' => 'nullable|string|max:100',
        ]);

        // 2. EL COLADOR (Sanitizar XSS)
        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        // 3. GUARDADO SEGURO (Usando los datos limpios en vez del $request directo)
        $asset = Asset::updateOrCreate(
            ['serial_number' => $validated['serial_number']],
            [
                'hostname'      => $validated['hostname'] ?? null,
                'ip_address'    => $validated['ip_address'] ?? null,
                'cpu'           => $validated['cpu'] ?? null,        
                'ram'           => $validated['ram'] ?? null,      
                'storage'       => $validated['storage'] ?? null,
                'model_version' => $validated['model_version'] ?? null,    
                'last_seen_at'  => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'ip_registrada' => $asset->ip_address
        ]);
    }


    public function heartbeat(Request $request)
    {
        // Añadimos límite de longitud para evitar ataques de denegación de servicio (DoS)
        $validated = $request->validate([
            'mac_address' => 'required|string|max:50' 
        ]);

        // Buscamos usando el dato validado y limpio
        $asset = \App\Models\Asset::where('mac_address', strip_tags($validated['mac_address']))->first();

        if ($asset) {
            $asset->update([
                'last_seen_at' => now()
            ]);
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Heartbeat recibido. Equipo en línea.'
            ], 200);
        }

        return response()->json([
            'status' => 'error', 
            'message' => 'Equipo no encontrado en SOMA.'
        ], 404);
    }}
