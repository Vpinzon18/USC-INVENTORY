<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AssetApiController;
use App\Http\Controllers\Api\InventoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas API del sistema SIGMA.
|
*/

/*
|--------------------------------------------------------------------------
| SIGMA AGENT
|--------------------------------------------------------------------------
|
| Prefijo:
| /api/v1/agent/...
|
*/

Route::prefix('v1/agent')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ZONA PÚBLICA
    |--------------------------------------------------------------------------
    */

    // Heartbeat del agente
    Route::post(
        'heartbeat',
        [InventoryController::class, 'heartbeat']
    );

    Route::get(
            'update',
            [AssetApiController::class, 'update']
        );


    /*
    |--------------------------------------------------------------------------
    | ZONA PROTEGIDA - SANCTUM
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        // =========================================================
        // INVENTARIO
        // =========================================================

        // Inventario completo enviado por SigmaAgent
        Route::post(
            'inventory',
            [InventoryController::class, 'report']
        );


        // =========================================================
        // CAMBIOS DIFERENCIALES
        // =========================================================

        // Registra únicamente cambios detectados en el equipo
        Route::post(
            'changes',
            [AssetApiController::class, 'changes']
        );


        // =========================================================
        // CONFIGURACIÓN REMOTA
        // =========================================================

        // El agente consulta configuración/instrucciones
        Route::get(
            'config',
            [AssetApiController::class, 'config']
        );


        // =========================================================
        // ACTUALIZACIÓN DEL AGENTE
        // =========================================================

        // El agente consulta si existe una nueva versión
        

    });

});