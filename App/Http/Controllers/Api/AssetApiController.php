<?php

namespace App\Http\Controllers\Api; // <-- Importante: Debe decir \Api

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetApiController extends Controller // <-- Nombre exacto del archivo
{
    public function report(Request $request) 
    {
        // Validamos los datos que vienen del C#
        $request->validate([
            'serial_number' => 'required',
            'hostname' => 'required',
        ]);

        // Guardamos o actualizamos en Postgres
        Asset::updateOrCreate(
            ['serial_number' => $request->serial_number],
            [
                'hostname' => $request->hostname,
                'cpu' => $request->cpu,
                'ram' => $request->ram,
                'storage' => $request->storage,
                'last_seen_at' => now(),
                'ip_address' => $request->ip()
            ]
        );

        return response()->json(['message' => 'Reporte recibido correctamente'], 200);
    }
}