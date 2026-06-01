<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Campus;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon; // <--- IMPORTANTE: Necesario para los cálculos de tiempo

class InventoryController extends Controller
{
    public function index()
{
    // 1. Carga optimizada (Usamos 'room.building.campus' para navegar hasta la sede)
    $assets = Asset::with(['room.building.campus', 'software'])->latest()->get();
    
    // 2. Datos para la tabla de "Recientes" (los 5 últimos)
    $recentAssets = $assets->take(5);

    // 3. Métricas clave (Tarjetas del Dashboard)
    $total = $assets->count();
    $online = $assets->filter(function ($asset) {
        return $asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->gt(now()->subMinutes(10));
    })->count();
    
    $offlineCount = $total - $online;

    // 4. Métricas extras
    $totalUsers = \App\Models\User::count();
    $totalCampuses = \App\Models\Campus::count();
    $totalRooms = \App\Models\Room::count();

    // 5. Datos para las Gráficas (SOLUCIÓN SEGURA)
    // Usamos el método map sobre los campus para contar los assets filtrando la colección
    $assetsByCampus = \App\Models\Campus::all()->map(function($campus) use ($assets) {
        $count = $assets->filter(function($asset) use ($campus) {
            // Navegamos por la relación: Asset -> Room -> Building -> Campus
            return $asset->room?->building?->campus_id == $campus->id;
        })->count();
        
        return [
            'name' => $campus->name,
            'total' => $count
        ];
    });

    // Retornamos todo a la vista
    return view('dashboard', compact(
        'assets', 
        'recentAssets',
        'total', 
        'online', 
        'offlineCount', 
        'totalUsers', 
        'totalCampuses', 
        'totalRooms', 
        'assetsByCampus'
    ));
}

    public function report(Request $request)
    { 
        $asset = Asset::updateOrCreate(
            ['serial_number' => $request->serial_number],
            [
                'hostname'   => $request->hostname,
                'ip_address' => $request->ip_address,
                'cpu'        => $request->cpu,        
                'ram'        => $request->ram,      
                'storage'    => $request->storage,    
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'ip_registrada' => $asset->ip_address
        ]);
    }
}