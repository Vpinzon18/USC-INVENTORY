<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\InventoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // 1. IMPORTANTE: Añadir esta línea
use App\Models\Asset;

// 1. PÁGINA DE INICIO / LOGIN
Route::get('/', function () {
    // Usamos el Facade Auth para evitar el error P1013 de Intelephense
    if (Auth::check()) { 
        return redirect()->route('selector');
    }
    return view('auth.login');
})->name('login');

// 2. RUTA POST PARA PROCESAR EL LOGIN
// Esto soluciona el error "MethodNotAllowedHttpException" al presionar el botón
Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('login.post');

// 3. EL SELECTOR DE MÓDULOS (Tu página welcome)
Route::get('/seleccion', function () {
    return view('welcome');
})->middleware(['auth'])->name('selector');

// 4. EL DASHBOARD DE INVENTARIO
Route::get('/dashboard', [InventoryController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// RUTAS PROTEGIDAS PARA ADMINISTRADORES (ROL 1)
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::resource('users', UserController::class);
});

// RUTAS DE PERFIL
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';