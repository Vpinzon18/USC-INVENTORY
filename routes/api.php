<?php

use App\Http\Controllers\Api\AssetApiController;
use App\Http\Controllers\Api\InventoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Agente SIGMA (USC) - Arquitectura Zero Trust
|--------------------------------------------------------------------------
| Las rutas de sincronización están protegidas ÚNICAMENTE por el token Sanctum.
| Esto permite que los equipos portátiles reporten su estado desde cualquier red.
*/

// 1. ZONA PROTEGIDA POR TOKEN (Para el reporte de inventario pesado)
Route::middleware('auth:sanctum')->group(function () {
    
    // El Agente envía la información del hardware, ubicación, etc.
    Route::post('assets/report', [AssetApiController::class, 'report']);
    
});

// 2. ZONA PÚBLICA (Solo para el "latido" o comprobación de conectividad)
// No transmite datos sensibles, solo confirma que el Agente y el Servidor pueden verse.
Route::post('/agent/heartbeat', [InventoryController::class, 'heartbeat']);