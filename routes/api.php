<?php

use App\Http\Controllers\Api\AssetApiController;
use App\Http\Controllers\Api\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('assets/report', [AssetApiController::class, 'report']);

});

Route::post('/agent/heartbeat', [InventoryController::class, 'heartbeat']);
