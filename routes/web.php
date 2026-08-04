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
use App\Http\Controllers\MovementController;
use App\Http\Controllers\CustodianController;
use App\Http\Controllers\Admin\AssetController; 
use App\Http\Controllers\TechnicalServiceController;
use App\Http\Controllers\Admin\MaintenanceScheduleController;
use App\Http\Controllers\DependencyController; 
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\Api\FilterApiController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoomTypeController;

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

Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])
    ->name('users.reset-password');

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

Route::get('/schedules/calendar', function () {
        return view('admin.schedules.calendar');
    })->name('schedules.calendar');

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

// =========================================================
// RUTAS API INTERNAS (Para componentes dinámicos y AJAX)
// =========================================================
Route::prefix('api/filters')->name('api.filters.')->group(function () {

    // Búsqueda dinámica de Responsables
    Route::get('/custodians', [FilterApiController::class, 'custodians'])
        ->name('custodians');

    // Búsqueda dinámica de Ubicaciones / Salones
    Route::get('/locations', [FilterApiController::class, 'locations'])
        ->name('locations');
    Route::get('/campuses', [FilterApiController::class, 'campuses'])->name('campuses');
    Route::get('/buildings', [FilterApiController::class, 'buildings'])->name('buildings');

    Route::get('/dependencies', [FilterApiController::class, 'dependencies'])->name('api.dependencies');
    Route::get('/job-titles', [FilterApiController::class, 'jobTitles'])->name('api.job-titles');


// Esta es la definición correcta
Route::get('/api/rooms-status', [CustodianController::class, 'getRoomsData'])->name('api.rooms.status');

    Route::prefix('api/sigma-filters')->group(function () {
        Route::get('/sedes', [FilterApiController::class, 'getSedes']);
        Route::get('/buildings', [FilterApiController::class, 'getBuildings']);
        Route::get('/rooms', [FilterApiController::class, 'getRooms']);
        Route::get('/dependencies', [FilterApiController::class, 'getDependencies']);
        Route::get('/technicians', [FilterApiController::class, 'getTechnicians']);
        Route::get('/assets', [\App\Http\Controllers\Api\FilterApiController::class, 'getAssets']);
        Route::get('/assets-global', [\App\Http\Controllers\Api\FilterApiController::class, 'getGlobalAssets']);
        Route::get('/offices', [\App\Http\Controllers\Api\FilterApiController::class, 'searchOffices']);
        Route::get('/categories', [FilterApiController::class, 'getCategories']);
        Route::get('/room-types', [FilterApiController::class, 'searchRoomTypes']);
    });


});
Route::get('maintenances/{maintenance}/export', [TechnicalServiceController::class, 'export'])->name('maintenances.export');


Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // CRUD para el módulo de Categorías de Equipos
    Route::resource('categories', CategoryController::class)->except(['show']);
    
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ...
    Route::resource('room_types', RoomTypeController::class);
    // ...
});
require __DIR__.'/auth.php';