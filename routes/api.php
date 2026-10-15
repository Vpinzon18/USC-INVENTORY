<?php

use App\Http\Controllers\Api\AssetApiController;
use App\Http\Controllers\Api\InventoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SIGMA Agent API
|--------------------------------------------------------------------------
| Heartbeats and inventory writes require a valid Sanctum token. The public
| update manifest is read-only and rate-limited.
*/
Route::prefix('v1/agent')->name('api.v1.agent.')->group(function () {
    Route::get('update', [AssetApiController::class, 'update'])
        ->middleware('throttle:30,1')
        ->name('update');

    Route::get('update/download/{version}', [AssetApiController::class, 'downloadUpdate'])
        ->middleware(['auth:sanctum', 'throttle:30,1'])
        ->name('update.download');

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('heartbeat', [InventoryController::class, 'heartbeat'])->name('heartbeat');
        Route::post('inventory', [InventoryController::class, 'report'])->name('inventory');
        Route::post('changes', [AssetApiController::class, 'changes'])->name('changes');
        Route::get('config', [AssetApiController::class, 'config'])->name('config');
    });
});
