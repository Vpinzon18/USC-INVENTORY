<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetApiController; // Ajusta según tu namespace
use App\Http\Controllers\Api\InventoryController; // Ajusta según tu namespace

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde registras las rutas API para tu aplicación SIGMA.
| Todas estas rutas son cargadas por el RouteServiceProvider y se les 
| asignará automáticamente el prefijo "/api".
|
*/

// ====================================================================
// RUTAS DEL AGENTE SIGMA 2.0 (C#)
// Prefijo resultante: /api/v1/agent/...
// ====================================================================
Route::prefix('v1/agent')->group(function () {

    // ---------------------------------------------------------
    // 1. ZONA PÚBLICA (Sin Token)
    // ---------------------------------------------------------
    // Solo para el "latido". No transmite datos sensibles, solo 
    // confirma que el Agente y el Servidor pueden verse y reporta el Uptime.
    Route::post('heartbeat', [InventoryController::class, 'heartbeat']);


    // ---------------------------------------------------------
    // 2. ZONA PROTEGIDA (Requiere Bearer Token de Sanctum)
    // ---------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        
        // A. Inventario Completo (Reemplaza a tu antiguo 'assets/report')
        // El Agente envía toda la información del hardware, ubicación, SO, etc.
        Route::post('inventory', [InventoryController::class, 'report']);
        
        // B. Cambios Diferenciales
        // El Agente envía SOLO lo que cambió (Ej: Si le quitaron RAM al equipo)
        Route::post('changes', [AssetApiController::class, 'changes']);
        
        // C. Configuración Remota (GET)
        // El Agente pregunta al servidor si hay nuevas instrucciones o políticas
        Route::get('config', [AssetApiController::class, 'config']);
        
    });

});