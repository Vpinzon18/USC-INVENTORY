<?php

use App\Http\Controllers\Api\AssetApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('assets/report', [AssetApiController::class, 'report']);
});
