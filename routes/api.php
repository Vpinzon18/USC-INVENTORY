<?php

use App\Http\Controllers\Api\AssetApiController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\RemoteSupportSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SIGMA Agent API
|--------------------------------------------------------------------------
| Heartbeats and inventory writes require a valid Sanctum token. The public
| update manifest is read-only and rate-limited.
*/
Route::prefix('v1/agent')->name('api.v1.agent.')->group(function () {
    Route::post('enrollment-requests', [InventoryController::class, 'requestEnrollment'])
        ->middleware('throttle:5,1')
        ->name('enrollment-requests.create');
    Route::post('enrollment-requests/{requestId}/status', [InventoryController::class, 'enrollmentStatus'])
        ->middleware('throttle:30,1')
        ->name('enrollment-requests.status');

    Route::post('enroll', [InventoryController::class, 'enroll'])
        ->middleware('throttle:5,1')
        ->name('enroll');

    Route::get('update', [AssetApiController::class, 'update'])
        ->middleware('throttle:30,1')
        ->name('update');

    Route::get('update/download/{version}', [AssetApiController::class, 'downloadUpdate'])
        ->middleware(['auth:sanctum', 'agent-token-ability:sigma-agent.update', 'throttle:30,1'])
        ->name('update.download');

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('heartbeat', [InventoryController::class, 'heartbeat'])->name('heartbeat');
        Route::post('inventory', [InventoryController::class, 'report'])->name('inventory');
        Route::post('changes', [AssetApiController::class, 'changes'])
            ->middleware('agent-token-ability:sigma-agent.control')
            ->name('changes');
        Route::get('config', [AssetApiController::class, 'config'])
            ->middleware('agent-token-ability:sigma-agent.control')
            ->name('config');
        Route::prefix('remote-support')->middleware('throttle:20,1')->group(function () {
            Route::post('redeem', [RemoteSupportSessionController::class, 'redeem'])->name('remote-support.redeem');
            Route::post('verify', [RemoteSupportSessionController::class, 'verify'])->name('remote-support.verify');
            Route::post('finish', [RemoteSupportSessionController::class, 'finish'])->name('remote-support.finish');
        });
    });
});
