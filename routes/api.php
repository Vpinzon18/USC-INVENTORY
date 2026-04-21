<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryController;

// Esta es la ruta para tu APK de C#
Route::post('/inventory/report', [InventoryController::class, 'report']);