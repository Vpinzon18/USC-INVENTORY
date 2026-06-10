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
use App\Http\Controllers\Admin\AssetController; 
use App\Http\Controllers\TechnicalServiceController;
use App\Http\Controllers\Admin\MaintenanceScheduleController;
use App\Http\Controllers\DependencyController; 
use App\Http\Controllers\JobTitleController;

//  PÁGINA DE INICIO / LOGIN
Route::get('/', function () {
    
    if (Auth::check()) { 
        return redirect()->route('selector');
    }
    return view('auth.login');
})->name('login');

//  RUTA POST PARA PROCESAR EL LOGIN
Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('login.post');

//  EL SELECTOR DE MÓDULOS (Tu página welcome)
Route::get('/seleccion', function () {
    return view('welcome');
})->middleware(['auth'])->name('selector');

// EL DASHBOARD DE INVENTARIO
Route::get('/dashboard', [InventoryController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// RUTAS PROTEGIDAS PARA ADMINISTRADORES (ROL 1)
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::resource('users', UserController::class);
});

// RUTAS PROTEGIDAS PARA LA ADMINISTRACION DE LA INFRAESTRUCCTURA
Route::middleware(['auth' ,'role:1'])->prefix('admin')->group(function () {
    Route::resource('campuses', CampusController::class);
    Route::resource('buildings', BuildingController::class);
    Route::resource('rooms', RoomController::class);
});
Route::get('/schedules/search-assets', [App\Http\Controllers\Admin\MaintenanceScheduleController::class, 'searchAssets'])->name('schedules.search-assets');
// RUTAS DE PERFIL
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTAS PROTEGIDAS PARA LA ADMINISTRACION DEL SUB MODULO DE MOVIMIENTO DE ACTIVOS
// RUTAS PROTEGIDAS PARA LA ADMINISTRACION DEL SUB MODULO DE MOVIMIENTO DE ACTIVOS
Route::middleware(['auth', 'role:1,2'])->prefix('admin')->group(function () {
    
    // 🆕 NUEVAS RUTAS: Historial, Formulario de Edición y Guardado de Corrección
    Route::get('/movements', [MovementController::class, 'index'])->name('movements.index');
    Route::get('/movements/{id}/edit', [MovementController::class, 'edit'])->name('movements.edit');
    Route::put('/movements/{id}', [MovementController::class, 'update'])->name('movements.update');

    // 🛠️ RUTAS QUE YA TENÍAS: Creación Masiva y Exportación de Actas
    Route::get('/movements/mass', [MovementController::class, 'createMass'])->name('movements.mass.create');
    Route::post('/movements/mass', [MovementController::class, 'storeMass'])->name('movements.mass.store');
    Route::get('/movements/acta_entrega/{actaNumber}', [MovementController::class, 'exportActa'])->name('movements.exportActa');
});

// RUTAS PROTEGIDAS PARA LA ADMINISTRACION DEL SUB MODULO DE RESPONSABLES
Route::middleware(['auth' ,'role:1'])->prefix('admin')->group(function () {
Route::resource('custodians', CustodianController::class);
});

// RUTAS PROTEGIDAS PARA LA ADMINISTRACION DEL SUB MODULO DE BITACORAS GLOBAL
Route::resource('maintenances', TechnicalServiceController::class);
Route::get('/schedules', [MaintenanceScheduleController::class, 'index'])->name('schedules.index');
Route::middleware(['auth' ,'role:1,2'])->prefix('admin')->group(function () {
Route::get('/schedules/create', [MaintenanceScheduleController::class, 'create'])->name('schedules.create');
Route::post('/schedules', [MaintenanceScheduleController::class, 'store'])->name('schedules.store');
Route::get('/schedules/{schedule}/edit', [MaintenanceScheduleController::class, 'edit'])->name('schedules.edit');
Route::put('/schedules/{schedule}', [MaintenanceScheduleController::class, 'update'])->name('schedules.update');
Route::get('/schedules/search-assets', [App\Http\Controllers\Admin\MaintenanceScheduleController::class, 'searchAssets'])->name('schedules.search-assets');
Route::get('/schedules/export', [App\Http\Controllers\Admin\MaintenanceScheduleController::class, 'export'])->name('schedules.export'); 
Route::get('/maintenance/{maintenanceSchedule}/edit', [MaintenanceScheduleController::class, 'edit'])->name('maintenance.edit');
});

// RUTAS PROTEGIDAS PARA LA ADMINISTRACION DEL SUB MODULO DE INVENTARIO HV
Route::resource('assets', AssetController::class);
Route::get('assets/{asset}/preview', [AssetController::class, 'previewPdf'])->name('assets.preview');
Route::get('/admin/assets/{id}/download-pdf', [AssetController::class, 'downloadPdf'])->name('assets.download.pdf');

// RUTAS DE VALIDACION PARA EVITAR CONFLICTOS CON LOS MOVIMIENTOS DE ACTIVOS.
Route::post('/movements/validate-conflict', [App\Http\Controllers\MovementController::class, 'validateConflict'])->name('movements.validate-conflict');

// Solo dejamos 'auth' en el middleware
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    Route::resource('dependencies', DependencyController::class);
    Route::resource('jobtitles', JobTitleController::class);

});

require __DIR__.'/auth.php';