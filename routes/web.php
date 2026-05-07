<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\RoomController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\InventoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Asset;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\CustodianController;
use App\Http\Controllers\Admin\AssetController; // Asegúrate de que el controlador exista

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
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('campuses', CampusController::class);
    Route::resource('buildings', BuildingController::class);
    Route::resource('rooms', RoomController::class);
});

// RUTAS DE PERFIL
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/movements/mass', [MovementController::class, 'createMass'])->name('movements.mass.create');
Route::post('/movements/mass', [MovementController::class, 'storeMass'])->name('movements.mass.store');

// Rutas para la gestión de Responsables (Custodios)
Route::resource('custodians', CustodianController::class);

// Esta línea crea automáticamente las rutas para index, create, store, edit, etc.
Route::resource('assets', AssetController::class);


require __DIR__.'/auth.php';