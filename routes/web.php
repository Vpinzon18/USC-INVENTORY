<?php

use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\Assets\AssetIndexController;
use App\Http\Controllers\Admin\Assets\AssetPdfController;
use App\Http\Controllers\Admin\Assets\AssetRemoteSupportController;
use App\Http\Controllers\Admin\AgentEnrollmentRequestController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MaintenanceScheduleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\FilterApiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustodianController;
use App\Http\Controllers\DependencyController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RemoteSupportSessionController;
use App\Http\Controllers\TechnicalServiceController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public and authenticated entry points
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check() ? redirect()->route('selector') : view('auth.login');
})->name('login');

Route::post('/', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/seleccion', fn () => view('welcome'))->name('selector');

    // The dashboard view is intentionally left untouched; access still requires a session.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Solo para Administradores: modulo de administracion de infraestructura, usuarios y auditoria, con autenticación y roles de acceso.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/agent-enrollment-requests', [AgentEnrollmentRequestController::class, 'index'])
        ->name('admin.agent-enrollment-requests.index');
    Route::post('/admin/agent-enrollment-requests/{id}/approve', [AgentEnrollmentRequestController::class, 'approve'])
        ->whereNumber('id')->middleware('throttle:30,1')->name('admin.agent-enrollment-requests.approve');
    Route::post('/admin/agent-enrollment-requests/{id}/recover', [AgentEnrollmentRequestController::class, 'recover'])
        ->whereNumber('id')->middleware('throttle:10,1')->name('admin.agent-enrollment-requests.recover');
    Route::post('/admin/agent-enrollment-requests/{id}/deny', [AgentEnrollmentRequestController::class, 'deny'])
        ->whereNumber('id')->middleware('throttle:30,1')->name('admin.agent-enrollment-requests.deny');

    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs.index');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->middleware('throttle:6,1')
        ->name('users.reset-password');

    Route::prefix('admin')->group(function () {
        Route::resource('campuses', CampusController::class)->except(['show']);
        Route::resource('buildings', BuildingController::class);
        Route::resource('rooms', RoomController::class);
        Route::post('rooms/assign-dependency', [RoomController::class, 'assignDependency'])->name('rooms.assign-dependency');
        Route::resource('custodians', CustodianController::class)->except(['show']);
        Route::resource('dependencies', DependencyController::class)->except(['show']);
        Route::resource('jobtitles', JobTitleController::class);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::name('admin.')->group(function () {
            Route::resource('room_types', RoomTypeController::class)->except(['show']);
        });
    });
});

/*
|--------------------------------------------------------------------------
| Rutas del modulo de INVENTARIO / HV, con autenticación y roles de acceso.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:1,2,3'])->group(function () {
    Route::get('/assets', [AssetIndexController::class, 'index'])->name('assets.index');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->whereNumber('asset')->name('assets.show');
    Route::get('/assets/{asset}/preview', [AssetPdfController::class, 'previewPdf'])->name('assets.preview');
    Route::get('/admin/assets/{id}/download-pdf', [AssetPdfController::class, 'downloadPdf'])->name('assets.download.pdf');

    Route::prefix('admin')->group(function () {
        Route::get('/movements', [MovementController::class, 'index'])->name('movements.index');
        Route::get('/movements/acta_entrega/{actaNumber}', [MovementController::class, 'exportActa'])->name('movements.exportActa');
    });
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/admin/remote-support-sessions', [RemoteSupportSessionController::class, 'index'])
        ->name('admin.remote-support-sessions.index');
    Route::post('/assets/{asset}/remote-support-sessions', [RemoteSupportSessionController::class, 'create'])
        ->middleware('throttle:10,1')
        ->name('assets.remote-support.create');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
    Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
    Route::patch('/assets/{asset}/rustdesk', [AssetRemoteSupportController::class, 'updateRustDeskId'])
        ->middleware('role:1')
        ->name('assets.rustdesk.update');
    Route::match(['put', 'patch'], '/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');

    Route::post('/movements/validate-conflict', [MovementController::class, 'validateConflict'])->name('movements.validate-conflict');

    Route::prefix('admin')->group(function () {
        Route::get('/movements/{id}/edit', [MovementController::class, 'edit'])->name('movements.edit');
        Route::put('/movements/{id}', [MovementController::class, 'update'])->name('movements.update');
        Route::get('/movements/mass', [MovementController::class, 'createMass'])->name('movements.mass.create');
        Route::post('/movements/mass', [MovementController::class, 'storeMass'])->name('movements.mass.store');
    });
});

Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->middleware(['auth', 'role:1'])->name('assets.destroy');

/*
|--------------------------------------------------------------------------
| Rutas del modulo de cronogramas y mantenimientos, con autenticación y roles de acceso.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:1,2,3'])->group(function () {
    Route::get('/schedules', [MaintenanceScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/calendar', [MaintenanceScheduleController::class, 'calendar'])->name('schedules.calendar');
    Route::get('/maintenances', [TechnicalServiceController::class, 'index'])->name('maintenances.index');
    Route::get('/maintenances/{maintenance}/export', [TechnicalServiceController::class, 'export'])
        ->name('maintenances.export');

    Route::prefix('admin')->group(function () {
        Route::get('/schedules/export', [MaintenanceScheduleController::class, 'export'])->name('schedules.export');
    });
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/schedules/search-assets', [MaintenanceScheduleController::class, 'searchAssets'])->name('schedules.search-assets');
    Route::get('/admin/schedules/search-assets', [MaintenanceScheduleController::class, 'searchAssets'])
        ->name('admin.schedules.search-assets');

    Route::get('/admin/schedules/create', [MaintenanceScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/admin/schedules', [MaintenanceScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/admin/schedules/{schedule}/edit', [MaintenanceScheduleController::class, 'edit'])->middleware('role:1')->name('schedules.edit');
    Route::put('/admin/schedules/{schedule}', [MaintenanceScheduleController::class, 'update'])->middleware('role:1')->name('schedules.update');
    Route::get('/schedules/{schedule}/extension-requests/create', [\App\Http\Controllers\Admin\MaintenanceScheduleExtensionController::class, 'create'])->name('schedules.extensions.create');
    Route::post('/schedules/{schedule}/extension-requests', [\App\Http\Controllers\Admin\MaintenanceScheduleExtensionController::class, 'store'])->name('schedules.extensions.store');
    Route::get('/admin/maintenance/{schedule}/edit', [MaintenanceScheduleController::class, 'edit'])->middleware('role:1')->name('maintenance.edit');

    Route::get('/maintenances/create', [TechnicalServiceController::class, 'create'])->name('maintenances.create');
    Route::post('/maintenances', [TechnicalServiceController::class, 'store'])->name('maintenances.store');
    Route::get('/maintenances/{maintenance}/edit', [TechnicalServiceController::class, 'edit'])->name('maintenances.edit');
    Route::put('/maintenances/{maintenance}', [TechnicalServiceController::class, 'update'])->name('maintenances.update');
});

Route::middleware(['auth', 'role:1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/schedule-extension-requests', [\App\Http\Controllers\Admin\MaintenanceScheduleExtensionController::class, 'index'])->name('schedules.extensions.index');
    Route::post('/schedule-extension-requests/{extensionRequest}/approve', [\App\Http\Controllers\Admin\MaintenanceScheduleExtensionController::class, 'approve'])->name('schedules.extensions.approve');
    Route::post('/schedule-extension-requests/{extensionRequest}/reject', [\App\Http\Controllers\Admin\MaintenanceScheduleExtensionController::class, 'reject'])->name('schedules.extensions.reject');
});

Route::delete('/maintenances/{maintenance}', [TechnicalServiceController::class, 'destroy'])->middleware(['auth', 'role:1'])->name('maintenances.destroy');

/*
|--------------------------------------------------------------------------
|Rutas de filtros para las consultas de filtros autenticados por el middleware de autenticación y roles, con limitación de solicitudes para evitar abuso.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:1,2,3', 'throttle:120,1'])
    ->prefix('api/filters')
    ->name('api.filters.')
    ->group(function () {
        Route::get('/custodians', [FilterApiController::class, 'custodians'])->name('custodians');
        Route::get('/locations', [FilterApiController::class, 'locations'])->name('locations');
        Route::get('/campuses', [FilterApiController::class, 'campuses'])->name('campuses');
        Route::get('/buildings', [FilterApiController::class, 'buildings'])->name('buildings');
        Route::get('/dependencies', [FilterApiController::class, 'dependencies'])->name('api.dependencies');
        Route::get('/job-titles', [FilterApiController::class, 'jobTitles'])->name('api.job-titles');
        Route::get('/api/rooms-status', [CustodianController::class, 'getRoomsData'])->name('api.rooms.status');

        // Los catálogos de auditoría contienen datos de actividad y son exclusivos de administradores.
        Route::middleware('role:1')->group(function () {
            Route::get('/audit-users', [FilterApiController::class, 'auditUsers'])->name('audit-users');
            Route::get('/audit-actions', [FilterApiController::class, 'auditActions'])->name('audit-actions');
        });

        Route::prefix('api/sigma-filters')->group(function () {
            Route::get('/sedes', [FilterApiController::class, 'getSedes']);
            Route::get('/buildings', [FilterApiController::class, 'getBuildings']);
            Route::get('/rooms', [FilterApiController::class, 'getRooms']);
            Route::get('/dependencies', [FilterApiController::class, 'getDependencies']);
            Route::get('/technicians', [FilterApiController::class, 'getTechnicians']);
            Route::get('/assets', [FilterApiController::class, 'getAssets']);
            Route::get('/assets-global', [FilterApiController::class, 'getGlobalAssets']);
            Route::get('/offices', [FilterApiController::class, 'searchOffices']);
            Route::get('/categories', [FilterApiController::class, 'getCategories']);
            Route::get('/room-types', [FilterApiController::class, 'searchRoomTypes']);
        });
    });

require __DIR__.'/auth.php';
