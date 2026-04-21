<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class InventoryController extends Controller
{
    public function index()
{
    // 1. Consultamos todos los activos de la base de datos
    $assets = \App\Models\Asset::all();

    // 2. Retornamos la vista del dashboard de Breeze pasando los datos
    return view('dashboard', compact('assets'));
}
public function report(Request $request)
{ 
    $asset = Asset::updateOrCreate(
        ['serial_number' => $request->serial_number], // Busca por serial
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
        'ip_registrada' => $asset->ip_address // Esto es para que veas en C# qué IP guardó
    ]);
}
}