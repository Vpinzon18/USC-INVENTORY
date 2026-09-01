<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Campus;
use App\Models\Custodian;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class AssetController extends Controller
{
    /**
     * =========================================================
     * LISTADO DE ACTIVOS
     * =========================================================
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);

        /*
        |--------------------------------------------------------------------------
        | Nombres para filtros AJAX
        |--------------------------------------------------------------------------
        */

        $selectedCustodianName = $request->filled('custodian_id')
            ? Custodian::find($request->custodian_id)?->full_name
            : '';

        $selectedCampusName = $request->filled('campus_id')
            ? Campus::find($request->campus_id)?->name
            : '';

        $selectedBuildingName = $request->filled('building_id')
            ? \App\Models\Building::find($request->building_id)?->name
            : '';

        $selectedRoomName = $request->filled('room_id')
            ? Room::find($request->room_id)?->nomenclatura
            : '';

        /*
        |--------------------------------------------------------------------------
        | Versiones de sistema operativo
        |--------------------------------------------------------------------------
        */

        $osVersions = Asset::whereNotNull('os_version')
            ->distinct()
            ->orderBy('os_version')
            ->pluck('os_version');

        /*
        |--------------------------------------------------------------------------
        | CONSULTA PRINCIPAL
        |--------------------------------------------------------------------------
        |
        | Cargamos las relaciones nuevas para evitar consultas adicionales
        | desde las vistas.
        |
        */

        $query = Asset::with([
            'room.building.campus',
            'currentCustodian',

            // Componentes normalizados
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'monitors',
            'battery',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA GENERAL
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('serial_number', 'LIKE', "%{$search}%")
                    ->orWhere('hostname', 'LIKE', "%{$search}%")
                    ->orWhere('internal_code', 'LIKE', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTROS DE UBICACIÓN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('campus_id')) {

            $query->whereHas('room.building', function ($q) use ($request) {

                $q->where('campus_id', $request->campus_id);

            });
        }

        if ($request->filled('building_id')) {

            $query->whereHas('room', function ($q) use ($request) {

                $q->where('building_id', $request->building_id);

            });
        }

        if ($request->filled('room_id')) {

            $query->where('room_id', $request->room_id);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO RESPONSABLE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('custodian_id')) {

            $query->whereHas('currentAssignment', function ($q) use ($request) {

                $q->where('custodian_id', $request->custodian_id);

            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO SISTEMA OPERATIVO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('os_version')) {

            $query->where('os_version', $request->os_version);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO AGENTE SIGMA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('agent')) {

            $query->where(
                'is_agent_managed',
                $request->agent
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO FICHA TÉCNICA
        |--------------------------------------------------------------------------
        |
        | monitor_serial ya no pertenece a assets.
        | Ahora comprobamos si existe al menos un monitor relacionado.
        |
        */

        if ($request->filled('inventory_status')) {

            if ($request->inventory_status === 'completo') {

                $query
                    ->whereHas('monitors')
                    ->whereNotNull('keyboard_serial')
                    ->whereNotNull('security_guaya');

            } else {

                $query->where(function ($q) {

                    $q->whereDoesntHave('monitors')
                        ->orWhereNull('keyboard_serial')
                        ->orWhereNull('security_guaya');

                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO CONECTIVIDAD
        |--------------------------------------------------------------------------
        */

        if ($request->filled('connectivity')) {

            if ($request->connectivity === 'online') {

                $query->where(
                    'last_seen_at',
                    '>=',
                    now()->subMinutes(10)
                );

            } else {

                $query->where(function ($q) {

                    $q->where(
                        'last_seen_at',
                        '<',
                        now()->subMinutes(10)
                    )
                    ->orWhereNull('last_seen_at');

                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $assets = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPIs
        |--------------------------------------------------------------------------
        */

        $total = Asset::count();

        $online = Asset::where(
            'last_seen_at',
            '>=',
            now()->subMinutes(10)
        )->count();

        $offline = $total - $online;

        $agentManaged = Asset::where(
            'is_agent_managed',
            true
        )->count();

        $unassigned = Asset::whereDoesntHave(
            'assignments',
            function ($q) {
                $q->where('status', 'active');
            }
        )->count();

        /*
        |--------------------------------------------------------------------------
        | ACTIVOS CON FICHA INCOMPLETA
        |--------------------------------------------------------------------------
        */

        $incomplete = Asset::where(function ($query) {

            $query->whereNull('keyboard_serial')
                ->orWhereNull('security_guaya')
                ->orWhereDoesntHave('monitors');

        })->count();

        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.assets.index',
            compact(
                'assets',
                'search',
                'perPage',
                'total',
                'online',
                'offline',
                'agentManaged',
                'unassigned',
                'incomplete',
                'osVersions',
                'selectedCustodianName',
                'selectedCampusName',
                'selectedBuildingName',
                'selectedRoomName'
            )
        );
    }


    /**
     * =========================================================
     * FORMULARIO CREAR
     * =========================================================
     */
    public function create()
    {
        $rooms = Room::all();

        $custodians = Custodian::all();

        $campuses = Campus::all();

        return view(
            'admin.assets.create',
            compact(
                'rooms',
                'custodians',
                'campuses'
            )
        );
    }


    /**
     * =========================================================
     * CREAR ACTIVO
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | ASSET PRINCIPAL
            |--------------------------------------------------------------------------
            */

            'room_id' => 'required|exists:rooms,id',

            'serial_number' => 'required|unique:assets,serial_number',

            'internal_code' => 'nullable|unique:assets,internal_code',

            'hostname' => 'nullable|string|max:255',

            'model_version' => 'nullable|string|max:255',

            'os_version' => 'nullable|string|max:255',

            'domain_name' => 'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | ELEMENTOS ADMINISTRATIVOS
            |--------------------------------------------------------------------------
            */

            'custodian_id' => 'required|exists:custodians,id',

            'keyboard_serial' => 'nullable|string|max:255',

            'mouse_serial' => 'nullable|string|max:255',

            'security_guaya' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $request) {

            /*
            |--------------------------------------------------------------------------
            | CREAR ASSET
            |--------------------------------------------------------------------------
            */

            $assetData = [
                'room_id' => $validated['room_id'],
                'serial_number' => $validated['serial_number'],
                'internal_code' => $validated['internal_code'] ?? null,
                'hostname' => $validated['hostname'] ?? null,
                'model_version' => $validated['model_version'] ?? null,
                'os_version' => $validated['os_version'] ?? null,
                'domain_name' => $validated['domain_name'] ?? null,

                'keyboard_serial' =>
                    $validated['keyboard_serial'] ?? null,

                'mouse_serial' =>
                    $validated['mouse_serial'] ?? null,

                'security_guaya' =>
                    $validated['security_guaya'] ?? null,
            ];

            $asset = Asset::create($assetData);

            /*
            |--------------------------------------------------------------------------
            | ASIGNACIÓN
            |--------------------------------------------------------------------------
            */

            Assignment::create([
                'asset_id' => $asset->id,
                'custodian_id' => $validated['custodian_id'],
                'room_id' => $validated['room_id'],
                'status' => 'active',
                'started_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | COMPONENTES MANUALES
            |--------------------------------------------------------------------------
            |
            | Estos se sincronizarán posteriormente con las relaciones nuevas.
            | Aquí dejamos la creación preparada para el formulario nuevo.
            |
            */

            $this->syncManualComponents(
                $asset,
                $request
            );
        });

        return redirect()
            ->route('assets.index')
            ->with(
                'success',
                'Equipo registrado con éxito.'
            );
    }


    /**
     * =========================================================
     * FORMULARIO EDITAR
     * =========================================================
     */
    public function edit(Asset $asset)
    {
        /*
        |--------------------------------------------------------------------------
        | Cargar relaciones
        |--------------------------------------------------------------------------
        */

        $asset->load([
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'monitors',
            'battery',
            'room',
            'currentCustodian',
        ]);

        $rooms = Room::all();

        $custodians = Custodian::all();

        $campuses = Campus::all();

        return view(
            'admin.assets.edit',
            compact(
                'asset',
                'rooms',
                'custodians',
                'campuses'
            )
        );
    }


    /**
     * =========================================================
     * ACTUALIZAR ACTIVO
     * =========================================================
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | ASSET PRINCIPAL
            |--------------------------------------------------------------------------
            */

            'room_id' => 'required|exists:rooms,id',

            'serial_number' =>
                'required|unique:assets,serial_number,' . $asset->id,

            'internal_code' =>
                'nullable|unique:assets,internal_code,' . $asset->id,

            'custodian_id' =>
                'nullable|exists:custodians,id',

            'hostname' =>
                'nullable|string|max:255',

            'model_version' =>
                'nullable|string|max:255',

            'os_version' =>
                'nullable|string|max:255',

            'domain_name' =>
                'nullable|string|max:255',

            'keyboard_serial' =>
                'nullable|string|max:255',

            'mouse_serial' =>
                'nullable|string|max:255',

            'security_guaya' =>
                'nullable|string|max:255',
        ]);

        DB::transaction(function () use (
            $asset,
            $validated,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR ASSET
            |--------------------------------------------------------------------------
            */

            $asset->update([

                'room_id' =>
                    $validated['room_id'],

                'serial_number' =>
                    $validated['serial_number'],

                'internal_code' =>
                    $validated['internal_code'] ?? null,

                'hostname' =>
                    $validated['hostname'] ?? null,

                'model_version' =>
                    $validated['model_version'] ?? null,

                'os_version' =>
                    $validated['os_version'] ?? null,

                'domain_name' =>
                    $validated['domain_name'] ?? null,

                'keyboard_serial' =>
                    $validated['keyboard_serial'] ?? null,

                'mouse_serial' =>
                    $validated['mouse_serial'] ?? null,

                'security_guaya' =>
                    $validated['security_guaya'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR RESPONSABLE / ASIGNACIÓN
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['custodian_id'])) {

                $activeAssignment = $asset
                    ->assignments()
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                if ($activeAssignment) {

                    $activeAssignment->update([
                        'custodian_id' =>
                            $validated['custodian_id'],

                        'room_id' =>
                            $validated['room_id'],
                    ]);

                } else {

                    Assignment::create([
                        'asset_id' => $asset->id,

                        'custodian_id' =>
                            $validated['custodian_id'],

                        'room_id' =>
                            $validated['room_id'],

                        'status' => 'active',

                        'started_at' => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | COMPONENTES
            |--------------------------------------------------------------------------
            */

            $this->syncManualComponents(
                $asset,
                $request
            );
        });

        return redirect()
            ->route('assets.index')
            ->with(
                'success',
                'Hoja de Vida actualizada correctamente.'
            );
    }


    /**
     * =========================================================
     * PREVISUALIZACIÓN PDF
     * =========================================================
     */
    public function previewPdf(Asset $asset)
    {
        $asset->load([
            'currentCustodian',
            'room.building',
            'assignments',
            'technicalServices',

            // Relaciones nuevas
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'monitors',
            'battery',
            'software',
        ]);

        return view(
            'admin.assets.pdf_preview',
            compact('asset')
        );
    }


    /**
     * =========================================================
     * DESCARGAR PDF
     * =========================================================
     */
    public function downloadPdf(int $id)
    {
        $asset = Asset::with([

            'currentCustodian',

            'room.building',

            'currentCustodian.jobTitle',

            'currentCustodian.dependency',

            'technicalServices.user',

            // Relaciones nuevas
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'monitors',
            'battery',
            'software',

        ])->findOrFail($id);

        $html = view(
            'admin.assets.pdf_export',
            compact('asset')
        )->render();

        $pdf = Browsershot::html($html)
            ->setNodeBinary(
                'C:\Program Files\nodejs\node.exe'
            )
            ->setChromePath(
                'C:\Program Files\Google\Chrome\Application\chrome.exe'
            )
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'disable-gpu',
                'no-zygote',
            ])
            ->showBackground()
            ->format('Letter')
            ->setMargins(
                10,
                10,
                10,
                10
            )
            ->pdf();

        return response($pdf)
            ->header(
                'Content-Type',
                'application/pdf'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="Hoja_Vida_USC_' .
                $asset->internal_code .
                '.pdf"'
            );
    }


    /**
     * =========================================================
     * ELIMINAR ACTIVO
     * =========================================================
     */
    public function destroy(Asset $asset)
    {
        try {

            DB::transaction(function () use ($asset) {

                $asset->delete();

            });

            return redirect()
                ->route('assets.index')
                ->with(
                    'success',
                    'El activo ha sido dado de baja y eliminado del CMDB exitosamente.'
                );

        } catch (\Illuminate\Database\QueryException $e) {

            Log::error(
                'Error de integridad al eliminar Asset',
                [
                    'asset_id' => $asset->id,
                    'error' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('assets.index')
                ->with(
                    'error',
                    'No se puede dar de baja el equipo porque tiene historiales de movimiento o mantenimientos asociados en el sistema.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Error al eliminar Asset',
                [
                    'asset_id' => $asset->id,
                    'error' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('assets.index')
                ->with(
                    'error',
                    'Ocurrió un error al intentar eliminar el activo.'
                );
        }
    }


    /**
     * =========================================================
     * SINCRONIZAR COMPONENTES MANUALES
     * =========================================================
     *
     * Este método permite que el formulario manual pueda trabajar
     * con las tablas nuevas.
     *
     * IMPORTANTE:
     * Los nombres de los campos utilizados aquí corresponden a los
     * campos que actualmente existen en create/edit.
     *
     */
    private function syncManualComponents(
        Asset $asset,
        Request $request
    ): void {

        /*
        |--------------------------------------------------------------------------
        | CPU
        |--------------------------------------------------------------------------
        */

       if (
    $request->filled('storage_brand') ||
    $request->filled('storage_model') ||
    $request->filled('storage_serial_number') ||
    $request->filled('storage_firmware') ||
    $request->filled('storage_capacity_gb') ||
    $request->filled('storage_free_gb') ||
    $request->filled('storage_type') ||
    $request->filled('storage_health')
) {

    $asset->storageDevices()->create([

        'brand' => $request->input('storage_brand'),

        'model' => $request->input('storage_model'),

        'serial_number' =>
            $request->input('storage_serial_number'),

        'firmware' =>
            $request->input('storage_firmware'),

        'capacity_gb' =>
            $request->input('storage_capacity_gb'),

        'free_gb' =>
            $request->input('storage_free_gb'),

        'type' =>
            $request->input('storage_type'),

        'health' =>
            $request->input('storage_health'),

    ]);
}

        /*
        |--------------------------------------------------------------------------
        | GPU
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('gpu_brand') ||
            $request->filled('gpu_model')
        ) {

            $asset->gpus()->delete();

            $asset->gpus()->create([

                'brand' =>
                    $request->input('gpu_brand'),

                'model' =>
                    $request->input('gpu_model'),

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | RAM
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('ram_brand') ||
            $request->filled('ram_model') ||
            $request->filled('ram_capacity_gb')
        ) {

            $asset->ramModules()->delete();

            $asset->ramModules()->create([

                'manufacturer' =>
                    $request->input('ram_brand'),

                'model' =>
                    $request->input('ram_model'),

                'capacity_gb' =>
                    $request->input('ram_capacity_gb'),

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('storage_brand') ||
            $request->filled('storage_model')
        ) {

            $asset->storageDevices()->delete();

            $asset->storageDevices()->create([

                'brand' =>
                    $request->input('storage_brand'),

                'model' =>
                    $request->input('storage_model'),

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MONITOR
        |--------------------------------------------------------------------------
        |
        | monitor_asset era un campo antiguo de assets.
        | La tabla nueva utiliza:
        |
        | brand
        | model
        | serial_number
        |
        */

        if (
            $request->filled('monitor_serial') ||
            $request->filled('monitor_asset')
        ) {

            $asset->monitors()->delete();

            $asset->monitors()->create([

                'brand' =>
                    $request->input('monitor_asset'),

                'serial_number' =>
                    $request->input('monitor_serial'),

            ]);
        }
    }
}