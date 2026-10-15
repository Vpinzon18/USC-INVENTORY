<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Asset;
use App\Models\Room;
use App\Models\Campus;
use App\Models\Custodian;
use App\Models\Assignment;
use App\Models\AssetMonitor;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPdf\Facades\Pdf;

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
        $filters = $request->query();
        $search = $request->input('search');

        $perPage = \App\Support\PaginationOptions::fromRequest($request);

        /*
        |--------------------------------------------------------------------------
        | NOMBRES PARA FILTROS AJAX
        |--------------------------------------------------------------------------
        */

        $selectedCustodianName =
            $request->filled('custodian_id')
            ? Custodian::find(
                $request->custodian_id
            )?->full_name
            : '';

        $selectedCampusName =
            $request->filled('campus_id')
            ? Campus::find(
                $request->campus_id
            )?->name
            : '';

        $selectedBuildingName =
            $request->filled('building_id')
            ? \App\Models\Building::find(
                $request->building_id
            )?->name
            : '';

        $selectedRoomName =
            $request->filled('room_id')
            ? Room::find(
                $request->room_id
            )?->nomenclatura
            : '';


        /*
        |--------------------------------------------------------------------------
        | SISTEMAS OPERATIVOS
        |--------------------------------------------------------------------------
        */

        $osVersions = Asset::whereNotNull(
            'os_version'
        )
            ->distinct()
            ->orderBy('os_version')
            ->pluck('os_version');


        /*
        |--------------------------------------------------------------------------
        | CONSULTA PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $query = Asset::with([

            'room.building.campus',

            'currentCustodian',

            /*
            |--------------------------------------------------------------------------
            | HARDWARE NORMALIZADO
            |--------------------------------------------------------------------------
            */

            'processors',
            'ramModules',
            'storageDevices',
            'gpus',

            /*
            |--------------------------------------------------------------------------
            | RED NORMALIZADA
            |--------------------------------------------------------------------------
            */

            'network.adapters',

            /*
            |--------------------------------------------------------------------------
            | OTROS COMPONENTES
            |--------------------------------------------------------------------------
            */

            'monitors',
            'battery',
            'software',

        ]);


        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA GENERAL
        |--------------------------------------------------------------------------
        */

        if ($search) {

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'serial_number',
                        'ILIKE',
                        "%{$search}%"
                    )

                        ->orWhere(
                            'hostname',
                            'ILIKE',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'internal_code',
                            'ILIKE',
                            "%{$search}%"
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO SEDE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('campus_id')) {

            $query->whereHas(
                'room.building',
                function ($q) use ($request) {

                    $q->where(
                        'campus_id',
                        $request->campus_id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO EDIFICIO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('building_id')) {

            $query->whereHas(
                'room',
                function ($q) use ($request) {

                    $q->where(
                        'building_id',
                        $request->building_id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO SALÓN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('room_id')) {

            $query->where(
                'room_id',
                $request->room_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO RESPONSABLE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('custodian_id')) {

            $query->whereHas(
                'currentAssignment',
                function ($q) use ($request) {

                    $q->where(
                        'custodian_id',
                        $request->custodian_id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO SISTEMA OPERATIVO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('os_version')) {

            $query->where(
                'os_version',
                $request->os_version
            );
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
        */

        if ($request->filled('inventory_status')) {

            if (
                $request->inventory_status ===
                'completo'
            ) {

                $query
                    ->whereHas('monitors')
                    ->whereNotNull('keyboard_serial')
                    ->whereNotNull('security_guaya');
            } else {

                $query->where(
                    function ($q) {

                        $q->whereDoesntHave(
                            'monitors'
                        )

                            ->orWhereNull(
                                'keyboard_serial'
                            )

                            ->orWhereNull(
                                'security_guaya'
                            );
                    }
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO CONECTIVIDAD
        |--------------------------------------------------------------------------
        */

        if ($request->filled('connectivity')) {

            if (
                $request->connectivity ===
                'online'
            ) {

                $query->where(
                    'last_seen_at',
                    '>=',
                    now()->subMinutes(10)
                );
            } else {

                $query->where(
                    function ($q) {

                        $q->where(
                            'last_seen_at',
                            '<',
                            now()->subMinutes(10)
                        )

                            ->orWhereNull(
                                'last_seen_at'
                            );
                    }
                );
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

        $offline =
            $total -
            $online;

        $agentManaged = Asset::where(
            'is_agent_managed',
            true
        )->count();

        $unassigned = Asset::whereDoesntHave(
            'assignments',
            function ($q) {

                $q->where(
                    'status',
                    'active'
                );
            }
        )->count();

        $incomplete = Asset::where(
            function ($query) {

                $query->whereNull(
                    'keyboard_serial'
                )

                    ->orWhereNull(
                        'security_guaya'
                    )

                    ->orWhereDoesntHave(
                        'monitors'
                    );
            }
        )->count();


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
                'filters',
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
        $rooms =
            Room::all();

        $custodians =
            Custodian::query()->where('status', 'active')->with('rooms')->get();

        $campuses =
            Campus::all();

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
     * CREAR ACTIVO MANUAL
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | ACTIVO PRINCIPAL
            |--------------------------------------------------------------------------
            */

            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
                Rule::exists('custodian_room', 'room_id')
                    ->where(fn ($query) => $query->where('custodian_id', $request->input('custodian_id'))),
            ],

            'serial_number' =>
            'required|string|max:255|unique:assets,serial_number',

            'internal_code' =>
            'nullable|string|max:255|unique:assets,internal_code',

            'hostname' =>
            'nullable|string|max:255',

            'manufacturer' =>
            'nullable|string|max:255',

            'model_version' =>
            'nullable|string|max:255',

            'chassis_type' =>
            'nullable|string|max:255',

            'os_version' =>
            'nullable|string|max:255',

            'domain_name' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | MOTHERBOARD
            |--------------------------------------------------------------------------
            */

            'board_brand' =>
            'nullable|string|max:255',

            'board_model' =>
            'nullable|string|max:255',

            'board_serial' =>
            'nullable|string|max:255',

            'board_version' =>
            'nullable|string|max:255',

            'board_status' =>
            'nullable|string|max:255',

            /*
|--------------------------------------------------------------------------
| NETWORK
|--------------------------------------------------------------------------
*/

            'network' =>
            'nullable|array',

            'network.gateway' =>
            'nullable|ip',

            'network.dns_server' =>
            'nullable|ip',

            'network.dhcp_enabled' =>
            'nullable|boolean',

            'network.adapters' =>
            'nullable|array',

            'network.adapters.*.name' =>
            'nullable|string|max:255',

            'network.adapters.*.description' =>
            'nullable|string|max:255',

            'network.adapters.*.type' =>
            'nullable|string|max:255',

            'network.adapters.*.mac_address' => [
                'nullable',
                'string',
                'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/',
            ],

            'network.adapters.*.ipv4' =>
            'nullable|ipv4',

            'network.adapters.*.ipv6' =>
            'nullable|ipv6',

            'network.adapters.*.connected' =>
            'nullable|boolean',

            'network.adapters.*.is_primary' =>
            'nullable|boolean',

            'network.adapters.*.is_active' =>
            'nullable|boolean',



            /*
            |--------------------------------------------------------------------------
            | BIOS
            |--------------------------------------------------------------------------
            */

            'bios_manufacturer' =>
            'nullable|string|max:255',

            'bios_version' =>
            'nullable|string|max:255',

            'bios_serial' =>
            'nullable|string|max:255',

            'bios_release_date' =>
            'nullable|date',


            /*
            |--------------------------------------------------------------------------
            | WINDOWS
            |--------------------------------------------------------------------------
            */

            'windows_version' =>
            'nullable|string|max:255',

            'windows_build' =>
            'nullable|string|max:255',

            'windows_edition' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | SEGURIDAD
            |--------------------------------------------------------------------------
            */

            'license_status' =>
            'nullable|string|max:255',

            'secure_boot' =>
            'nullable|boolean',

            'bitlocker_status' =>
            'nullable|string|max:255',

            'tpm_version' =>
            'nullable|string|max:255',

            'antivirus' =>
            'nullable|string|max:255',

            'firewall_enabled' =>
            'nullable|boolean',

            'uptime_minutes' =>
            'nullable|integer|min:0',


            /*
            |--------------------------------------------------------------------------
            | ADMINISTRATIVO
            |--------------------------------------------------------------------------
            */

            'custodian_id' => [
                'required',
                'integer',
                Rule::exists('custodians', 'id')->where('status', 'active'),
            ],

            'keyboard_serial' =>
            'nullable|string|max:255',

            'mouse_serial' =>
            'nullable|string|max:255',

            'security_guaya' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | PROCESADOR
            |--------------------------------------------------------------------------
            */

            'processor' =>
            'nullable|array',

            'processor.brand' =>
            'nullable|string|max:255',

            'processor.model' =>
            'nullable|string|max:255',

            'processor.processor_id' =>
            'nullable|string|max:255',

            'processor.socket' =>
            'nullable|string|max:255',

            'processor.status' =>
            'nullable|string|max:255',

            'processor.cores' =>
            'nullable|integer|min:0',

            'processor.threads' =>
            'nullable|integer|min:0',

            'processor.speed_mhz' =>
            'nullable|integer|min:0',

            'processor.architecture' =>
            'nullable|string|max:255',

            'processor.family' =>
            'nullable|string|max:255',

            'processor.generation' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | RAM
            |--------------------------------------------------------------------------
            */

            'ram_modules' =>
            'nullable|array',

            'ram_modules.*.manufacturer' =>
            'nullable|string|max:255',

            'ram_modules.*.model' =>
            'nullable|string|max:255',

            'ram_modules.*.serial_number' =>
            'nullable|string|max:255',

            'ram_modules.*.bank' =>
            'nullable|string|max:255',

            'ram_modules.*.slot' =>
            'nullable|string|max:255',

            'ram_modules.*.memory_type' =>
            'nullable|string|max:255',

            'ram_modules.*.capacity_gb' =>
            'nullable|numeric|min:0',

            'ram_modules.*.speed_mhz' =>
            'nullable|integer|min:0',


            /*
            |--------------------------------------------------------------------------
            | STORAGE
            |--------------------------------------------------------------------------
            */

            'storage_devices' =>
            'nullable|array',

            'storage_devices.*.brand' =>
            'nullable|string|max:255',

            'storage_devices.*.model' =>
            'nullable|string|max:255',

            'storage_devices.*.serial_number' =>
            'nullable|string|max:255',

            'storage_devices.*.firmware' =>
            'nullable|string|max:255',

            'storage_devices.*.capacity_gb' =>
            'nullable|numeric|min:0',

            'storage_devices.*.free_gb' =>
            'nullable|numeric|min:0',

            'storage_devices.*.type' =>
            'nullable|string|max:255',

            'storage_devices.*.health' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | GPU
            |--------------------------------------------------------------------------
            */

            'gpus' =>
            'nullable|array',

            'gpus.*.brand' =>
            'nullable|string|max:255',

            'gpus.*.model' =>
            'nullable|string|max:255',

            'gpus.*.memory_mb' =>
            'nullable|integer|min:0',

            'gpus.*.driver_version' =>
            'nullable|string|max:255',

            'gpus.*.processor' =>
            'nullable|string|max:255',

            'gpus.*.resolution' =>
            'nullable|string|max:255',

            'gpus.*.refresh_rate' =>
            'nullable|integer|min:0',


            /*
            |--------------------------------------------------------------------------
            | BATERÍA
            |--------------------------------------------------------------------------
            |
            | TOTALMENTE OPCIONAL.
            |
            | Desktop = puede ir completamente vacío.
            |
            */

            'battery' =>
            'nullable|array',

            'battery.manufacturer' =>
            'nullable|string|max:255',

            'battery.model' =>
            'nullable|string|max:255',

            'battery.serial_number' =>
            'nullable|string|max:255',

            'battery.health' =>
            'nullable|string|max:255',

            'battery.cycle_count' =>
            'nullable|integer|min:0',

            'battery.design_capacity' =>
            'nullable|integer|min:0',

            'battery.full_charge_capacity' =>
            'nullable|integer|min:0',

            'battery.remaining_capacity' =>
            'nullable|integer|min:0',

            'battery.charge_percent' =>
            'nullable|integer|min:0|max:100',

            'battery.is_charging' =>
            'nullable|boolean',

            'battery.estimated_runtime_minutes' =>
            'nullable|integer|min:0|max:1440',


            /*
            |--------------------------------------------------------------------------
            | MONITORES
            |--------------------------------------------------------------------------
            */

            'monitors' =>
            'nullable|array',

            'monitors.*.brand' =>
            'nullable|string|max:255',

            'monitors.*.model' =>
            'nullable|string|max:255',

            'monitors.*.serial_number' =>
            'nullable|string|max:255',

            'monitors.*.manufacturer_code' =>
            'nullable|string|max:255',

            'monitors.*.year' =>
            'nullable|integer|min:0',

            'monitors.*.size' =>
            'nullable|numeric|min:0',

            'monitors.*.resolution' =>
            'nullable|string|max:255',

            'monitors.*.refresh_rate' =>
            'nullable|integer|min:0',

            'monitors.*.fixed_asset_code' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | SOFTWARE
            |--------------------------------------------------------------------------
            */

            'software' =>
            'nullable|array',

            'software.*.name' =>
            'nullable|string|max:255',

            'software.*.version' =>
            'nullable|string|max:255',
        ], [
                'network.gateway.ip' =>
                'La puerta de enlace debe ser una dirección IP válida.',

                'network.dns_server.ip' =>
                'El servidor DNS debe ser una dirección IP válida.',

                'network.adapters.*.mac_address.regex' =>
                'La dirección MAC debe tener el formato AA:BB:CC:DD:EE:FF.',

                'network.adapters.*.ipv4.ipv4' =>
                'La dirección IPv4 no es válida.',

                'network.adapters.*.ipv6.ipv6' =>
                'La dirección IPv6 no es válida.',

                'network.dhcp_enabled.boolean' =>
                'El valor DHCP debe ser verdadero o falso.',

                'network.adapters.*.connected.boolean' =>
                'El estado de conexión no es válido.',

                'network.adapters.*.is_primary.boolean' =>
                'El estado principal no es válido.',

                'network.adapters.*.is_active.boolean' =>
                'El estado activo no es válido.',
                'custodian_id.required' => 'Selecciona un custodiante activo.',
                'custodian_id.exists' => 'El custodiante seleccionado no existe o está inactivo.',
                'room_id.required' => 'Selecciona una ubicación para el activo.',
                'room_id.exists' => 'La ubicación debe estar asignada al custodiante seleccionado.',
            ]
        );


        DB::transaction(
            function () use (
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | ASSET PRINCIPAL
                |--------------------------------------------------------------------------
                */

                $asset = Asset::create([

                    'room_id' =>
                    $validated['room_id'],

                    'serial_number' =>
                    $validated['serial_number'],

                    'internal_code' =>
                    $validated['internal_code']
                        ?? null,

                    'hostname' =>
                    $validated['hostname']
                        ?? null,

                    'manufacturer' =>
                    $validated['manufacturer']
                        ?? null,

                    'model_version' =>
                    $validated['model_version']
                        ?? null,

                    'chassis_type' =>
                    $validated['chassis_type']
                        ?? null,

                    'os_version' =>
                    $validated['os_version']
                        ?? null,

                    'domain_name' =>
                    $validated['domain_name']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | MOTHERBOARD
                    |--------------------------------------------------------------------------
                    */

                    'board_brand' =>
                    $validated['board_brand']
                        ?? null,

                    'board_model' =>
                    $validated['board_model']
                        ?? null,

                    'board_serial' =>
                    $validated['board_serial']
                        ?? null,

                    'board_version' =>
                    $validated['board_version']
                        ?? null,

                    'board_status' =>
                    $validated['board_status']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | BIOS
                    |--------------------------------------------------------------------------
                    */

                    'bios_manufacturer' =>
                    $validated['bios_manufacturer']
                        ?? null,

                    'bios_version' =>
                    $validated['bios_version']
                        ?? null,

                    'bios_serial' =>
                    $validated['bios_serial']
                        ?? null,

                    'bios_release_date' =>
                    $validated['bios_release_date']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | WINDOWS
                    |--------------------------------------------------------------------------
                    */

                    'windows_version' =>
                    $validated['windows_version']
                        ?? null,

                    'windows_build' =>
                    $validated['windows_build']
                        ?? null,

                    'windows_edition' =>
                    $validated['windows_edition']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | SEGURIDAD
                    |--------------------------------------------------------------------------
                    */

                    'license_status' =>
                    $validated['license_status']
                        ?? null,

                    'secure_boot' =>
                    $validated['secure_boot']
                        ?? null,

                    'bitlocker_status' =>
                    $validated['bitlocker_status']
                        ?? null,

                    'tpm_version' =>
                    $validated['tpm_version']
                        ?? null,

                    'antivirus' =>
                    $validated['antivirus']
                        ?? null,

                    'firewall_enabled' =>
                    $validated['firewall_enabled']
                        ?? null,

                    'uptime_minutes' =>
                    $validated['uptime_minutes']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | PERIFÉRICOS
                    |--------------------------------------------------------------------------
                    */

                    'keyboard_serial' =>
                    $validated['keyboard_serial']
                        ?? null,

                    'mouse_serial' =>
                    $validated['mouse_serial']
                        ?? null,

                    'security_guaya' =>
                    $validated['security_guaya']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | ORIGEN
                    |--------------------------------------------------------------------------
                    */

                    'is_agent_managed' =>
                    false,

                    'is_online' =>
                    false,

                ]);


                /*
                |--------------------------------------------------------------------------
                | ASIGNACIÓN INICIAL
                |--------------------------------------------------------------------------
                */

                Assignment::create([

                    'asset_id' =>
                    $asset->id,

                    'custodian_id' =>
                    $validated['custodian_id'],

                    'room_id' =>
                    $validated['room_id'],

                    'status' =>
                    'active',

                    'started_at' =>
                    now(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | COMPONENTES NORMALIZADOS
                |--------------------------------------------------------------------------
                */

                $this->syncManualComponents(
                    $asset,
                    $validated
                );
            }
        );


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
        $asset->load([

            'processors',

            'ramModules',

            'storageDevices',

            'gpus',

            'network.adapters',

            'monitors',

            'battery',

            'software',

            'room.building.campus',

            'currentCustodian',

        ]);


        $rooms =
            Room::all();

        $custodians =
            Custodian::all();

        $campuses =
            Campus::all();


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
public function update(
    Request $request,
    Asset $asset
) {

    $agentManaged =
        (bool) $asset->is_agent_managed;


    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATIVO
        |--------------------------------------------------------------------------
        */

        'room_id' =>
        'required|exists:rooms,id',

        'internal_code' =>
        'nullable|string|max:255|unique:assets,internal_code,' .
            $asset->id,

        'custodian_id' =>
        'nullable|exists:custodians,id',

        'keyboard_serial' =>
        'nullable|string|max:255',

        'mouse_serial' =>
        'nullable|string|max:255',

        'security_guaya' =>
        'nullable|string|max:255',


        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN GENERAL
        |--------------------------------------------------------------------------
        */

        'serial_number' =>
        'required|string|max:255|unique:assets,serial_number,' .
            $asset->id,

        'hostname' =>
        'nullable|string|max:255',

        'manufacturer' =>
        'nullable|string|max:255',

        'model_version' =>
        'nullable|string|max:255',

        'chassis_type' =>
        'nullable|string|max:255',

        'os_version' =>
        'nullable|string|max:255',

        'domain_name' =>
        'nullable|string|max:255',


        /*
        |--------------------------------------------------------------------------
        | MOTHERBOARD
        |--------------------------------------------------------------------------
        */

        'board_brand' =>
        'nullable|string|max:255',

        'board_model' =>
        'nullable|string|max:255',

        'board_serial' =>
        'nullable|string|max:255',

        'board_version' =>
        'nullable|string|max:255',

        'board_status' =>
        'nullable|string|max:255',


        /*
        |--------------------------------------------------------------------------
        | BIOS
        |--------------------------------------------------------------------------
        */

        'bios_manufacturer' =>
        'nullable|string|max:255',

        'bios_version' =>
        'nullable|string|max:255',

        'bios_serial' =>
        'nullable|string|max:255',

        'bios_release_date' =>
        'nullable|date',


        /*
        |--------------------------------------------------------------------------
        | WINDOWS
        |--------------------------------------------------------------------------
        */

        'windows_version' =>
        'nullable|string|max:255',

        'windows_build' =>
        'nullable|string|max:255',

        'windows_edition' =>
        'nullable|string|max:255',


        /*
        |--------------------------------------------------------------------------
        | SEGURIDAD
        |--------------------------------------------------------------------------
        */

        'license_status' =>
        'nullable|string|max:255',

        'secure_boot' =>
        'nullable|boolean',

        'bitlocker_status' =>
        'nullable|string|max:255',

        'tpm_version' =>
        'nullable|string|max:255',

        'antivirus' =>
        'nullable|string|max:255',

        'firewall_enabled' =>
        'nullable|boolean',

        'uptime_minutes' =>
        'nullable|integer|min:0',


        /*
        |--------------------------------------------------------------------------
        | COMPONENTES
        |--------------------------------------------------------------------------
        */

        'processor' =>
        'nullable|array',

        'processor.brand' =>
        'nullable|string|max:255',

        'processor.model' =>
        'nullable|string|max:255',

        'processor.processor_id' =>
        'nullable|string|max:255',

        'processor.socket' =>
        'nullable|string|max:255',

        'processor.status' =>
        'nullable|string|max:255',

        'processor.cores' =>
        'nullable|integer|min:0',

        'processor.threads' =>
        'nullable|integer|min:0',

        'processor.speed_mhz' =>
        'nullable|integer|min:0',

        'processor.architecture' =>
        'nullable|string|max:255',

        'processor.family' =>
        'nullable|string|max:255',

        'processor.generation' =>
        'nullable|string|max:255',


        /*
        |--------------------------------------------------------------------------
        | RAM
        |--------------------------------------------------------------------------
        */

        'ram_modules' =>
        'nullable|array',

        'ram_modules.*.manufacturer' =>
        'nullable|string|max:255',

        'ram_modules.*.model' =>
        'nullable|string|max:255',

        'ram_modules.*.serial_number' =>
        'nullable|string|max:255',

        'ram_modules.*.bank' =>
        'nullable|string|max:255',

        'ram_modules.*.slot' =>
        'nullable|string|max:255',

        'ram_modules.*.memory_type' =>
        'nullable|string|max:255',

        'ram_modules.*.capacity_gb' =>
        'nullable|numeric|min:0',

        'ram_modules.*.speed_mhz' =>
        'nullable|integer|min:0',


        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        'storage_devices' =>
        'nullable|array',

        'storage_devices.*.brand' =>
        'nullable|string|max:255',

        'storage_devices.*.model' =>
        'nullable|string|max:255',

        'storage_devices.*.serial_number' =>
        'nullable|string|max:255',

        'storage_devices.*.firmware' =>
        'nullable|string|max:255',

        'storage_devices.*.capacity_gb' =>
        'nullable|numeric|min:0',

        'storage_devices.*.free_gb' =>
        'nullable|numeric|min:0',

        'storage_devices.*.type' =>
        'nullable|string|max:255',

        'storage_devices.*.health' =>
        'nullable|string|max:255',


        /*
        |--------------------------------------------------------------------------
        | GPU
        |--------------------------------------------------------------------------
        */

        'gpus' =>
        'nullable|array',

        'gpus.*.brand' =>
        'nullable|string|max:255',

        'gpus.*.model' =>
        'nullable|string|max:255',

        'gpus.*.memory_mb' =>
        'nullable|integer|min:0',

        'gpus.*.driver_version' =>
        'nullable|string|max:255',

        'gpus.*.processor' =>
        'nullable|string|max:255',

        'gpus.*.resolution' =>
        'nullable|string|max:255',

        'gpus.*.refresh_rate' =>
        'nullable|integer|min:0',


        /*
        |--------------------------------------------------------------------------
        | RED
        |--------------------------------------------------------------------------
        */

        'network' =>
        'nullable|array',

        'network.gateway' =>
        'nullable|ip',

        'network.dns_server' =>
        'nullable|ip',

        'network.dhcp_enabled' =>
        'nullable|boolean',

        'network.adapters' =>
        'nullable|array',

        'network.adapters.*.name' =>
        'nullable|string|max:255',

        'network.adapters.*.description' =>
        'nullable|string|max:255',

        'network.adapters.*.type' =>
        'nullable|string|max:255',

        'network.adapters.*.mac_address' => [
            'nullable',
            'string',
            'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/',
        ],

        'network.adapters.*.ipv4' =>
        'nullable|ipv4',

        'network.adapters.*.ipv6' =>
        'nullable|ipv6',

        'network.adapters.*.connected' =>
        'nullable|boolean',

        'network.adapters.*.is_primary' =>
        'nullable|boolean',

        'network.adapters.*.is_active' =>
        'nullable|boolean',


        /*
        |--------------------------------------------------------------------------
        | BATERÍA
        |--------------------------------------------------------------------------
        */

        'battery' =>
        'nullable|array',

        'battery.manufacturer' =>
        'nullable|string|max:255',

        'battery.model' =>
        'nullable|string|max:255',

        'battery.serial_number' =>
        'nullable|string|max:255',

        'battery.health' =>
        'nullable|string|max:255',

        'battery.cycle_count' =>
        'nullable|integer|min:0',

        'battery.design_capacity' =>
        'nullable|integer|min:0',

        'battery.full_charge_capacity' =>
        'nullable|integer|min:0',

        'battery.remaining_capacity' =>
        'nullable|integer|min:0',

        'battery.charge_percent' =>
        'nullable|integer|min:0|max:100',

        'battery.is_charging' =>
        'nullable|boolean',

        'battery.estimated_runtime_minutes' =>
        'nullable|integer|min:0|max:1440',


        /*
        |--------------------------------------------------------------------------
        | SOFTWARE
        |--------------------------------------------------------------------------
        */

        'software' =>
        'nullable|array',

        'software.*.is_active' =>
        'required|boolean',


        /*
        |--------------------------------------------------------------------------
        | MONITORES
        |--------------------------------------------------------------------------
        */

        'monitors' =>
        'nullable|array',

        'monitors.*.id' =>
        'nullable|integer',

        'monitors.*.brand' =>
        'nullable|string|max:255',

        'monitors.*.model' =>
        'nullable|string|max:255',

        'monitors.*.serial_number' =>
        'nullable|string|max:255',

        'monitors.*.manufacturer_code' =>
        'nullable|string|max:255',

        'monitors.*.year' =>
        'nullable|integer|min:0',

        'monitors.*.size' =>
        'nullable|numeric|min:0',

        'monitors.*.resolution' =>
        'nullable|string|max:255',

        'monitors.*.refresh_rate' =>
        'nullable|integer|min:0',

        'monitors.*.fixed_asset_code' =>
        'nullable|string|max:255',

    ]);


    /*
    |--------------------------------------------------------------------------
    | TRANSACCIÓN
    |--------------------------------------------------------------------------
    */

    DB::transaction(
        function () use (
            $asset,
            $validated,
            $request,
            $agentManaged
        ) {

            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN ADMINISTRATIVA
            |--------------------------------------------------------------------------
            */

            $asset->update([

                'room_id' =>
                $validated['room_id'],

                'internal_code' =>
                $validated['internal_code']
                    ?? null,

                'keyboard_serial' =>
                $validated['keyboard_serial']
                    ?? null,

                'mouse_serial' =>
                $validated['mouse_serial']
                    ?? null,

                'security_guaya' =>
                $validated['security_guaya']
                    ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN TÉCNICA
            |--------------------------------------------------------------------------
            |
            | SOLO activos MANUALES.
            |
            */

            if (!$agentManaged) {

                $asset->update([

                    'serial_number' =>
                    $validated['serial_number'],

                    'hostname' =>
                    $validated['hostname']
                        ?? null,

                    'manufacturer' =>
                    $validated['manufacturer']
                        ?? null,

                    'model_version' =>
                    $validated['model_version']
                        ?? null,

                    'chassis_type' =>
                    $validated['chassis_type']
                        ?? null,

                    'os_version' =>
                    $validated['os_version']
                        ?? null,

                    'domain_name' =>
                    $validated['domain_name']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | MOTHERBOARD
                    |--------------------------------------------------------------------------
                    */

                    'board_brand' =>
                    $validated['board_brand']
                        ?? null,

                    'board_model' =>
                    $validated['board_model']
                        ?? null,

                    'board_serial' =>
                    $validated['board_serial']
                        ?? null,

                    'board_version' =>
                    $validated['board_version']
                        ?? null,

                    'board_status' =>
                    $validated['board_status']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | BIOS
                    |--------------------------------------------------------------------------
                    */

                    'bios_manufacturer' =>
                    $validated['bios_manufacturer']
                        ?? null,

                    'bios_version' =>
                    $validated['bios_version']
                        ?? null,

                    'bios_serial' =>
                    $validated['bios_serial']
                        ?? null,

                    'bios_release_date' =>
                    $validated['bios_release_date']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | WINDOWS
                    |--------------------------------------------------------------------------
                    */

                    'windows_version' =>
                    $validated['windows_version']
                        ?? null,

                    'windows_build' =>
                    $validated['windows_build']
                        ?? null,

                    'windows_edition' =>
                    $validated['windows_edition']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | SEGURIDAD
                    |--------------------------------------------------------------------------
                    */

                    'license_status' =>
                    $validated['license_status']
                        ?? null,

                    'secure_boot' =>
                    $validated['secure_boot']
                        ?? null,

                    'bitlocker_status' =>
                    $validated['bitlocker_status']
                        ?? null,

                    'tpm_version' =>
                    $validated['tpm_version']
                        ?? null,

                    'antivirus' =>
                    $validated['antivirus']
                        ?? null,

                    'firewall_enabled' =>
                    $validated['firewall_enabled']
                        ?? null,

                    'uptime_minutes' =>
                    $validated['uptime_minutes']
                        ?? null,

                ]);


                /*
                |--------------------------------------------------------------------------
                | HARDWARE NORMALIZADO
                |--------------------------------------------------------------------------
                */

                $this->syncManualComponents(
                    $asset,
                    $validated
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CÓDIGO ACTIVO FIJO DE MONITORES
            |--------------------------------------------------------------------------
            |
            | En equipos SIGMA solamente permitimos
            | modificar el código administrativo.
            |
            */

            if ($agentManaged) {

                foreach (
                    $request->input(
                        'monitors',
                        []
                    ) as $monitorId => $data
                ) {

                    if (
                        !is_numeric($monitorId) ||
                        !is_array($data)
                    ) {
                        continue;
                    }


                    if (
                        !array_key_exists(
                            'fixed_asset_code',
                            $data
                        )
                    ) {
                        continue;
                    }


                    AssetMonitor::where(
                        'id',
                        $monitorId
                    )
                        ->where(
                            'asset_id',
                            $asset->id
                        )
                        ->update([

                            'fixed_asset_code' =>
                            $data['fixed_asset_code'],

                        ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | AUTORIZACIÓN DE SOFTWARE
            |--------------------------------------------------------------------------
            |
            | El SIGMA Agent detecta el software.
            | El administrador controla únicamente is_active.
            |
            | No eliminamos ni recreamos los registros.
            |
            */

            if ($agentManaged) {

                foreach (
                    $validated['software'] ?? []
                    as $softwareId => $softwareData
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Verificar que el software pertenece al activo
                    |--------------------------------------------------------------------------
                    */

                    $software = $asset
                        ->software()
                        ->where(
                            'id',
                            $softwareId
                        )
                        ->first();

                    if (!$software) {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Actualizar solamente autorización
                    |--------------------------------------------------------------------------
                    */

                    $software->update([

                        'is_active' =>
                        (bool) $softwareData['is_active'],

                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | ASIGNACIÓN
            |--------------------------------------------------------------------------
            */

            if (
                !empty($validated['custodian_id'])
            ) {

                $activeAssignment =
                    $asset
                        ->assignments()
                        ->where(
                            'status',
                            'active'
                        )
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

                        'asset_id' =>
                        $asset->id,

                        'custodian_id' =>
                        $validated['custodian_id'],

                        'room_id' =>
                        $validated['room_id'],

                        'status' =>
                        'active',

                        'started_at' =>
                        now(),

                    ]);
                }
            }
        }
    );


    return redirect()
        ->route('assets.index')
        ->with(
            'success',
            'Hoja de Vida actualizada correctamente.'
        );
}


    /**
     * =========================================================
     * SINCRONIZAR COMPONENTES MANUALES
     * =========================================================
     *
     * Todos los componentes técnicos normalizados
     * se almacenan en sus respectivas tablas.
     *
     * NO se duplican en assets.
     *
     */
    private function syncManualComponents(
        Asset $asset,
        array $data
    ): void {

        /*
        |--------------------------------------------------------------------------
        | PROCESADOR
        |--------------------------------------------------------------------------
        */

        $processorData =
            $data['processor']
            ?? [];


        if (
            $this->hasData(
                $processorData
            )
        ) {

            $asset->processors()->updateOrCreate(

                [
                    'asset_id' =>
                    $asset->id,
                ],

                [

                    'brand' =>
                    $processorData['brand']
                        ?? null,

                    'model' =>
                    $processorData['model']
                        ?? null,

                    'processor_id' =>
                    $processorData['processor_id']
                        ?? null,

                    'socket' =>
                    $processorData['socket']
                        ?? null,

                    'status' =>
                    $processorData['status']
                        ?? null,

                    'cores' =>
                    $processorData['cores']
                        ?? null,

                    'threads' =>
                    $processorData['threads']
                        ?? null,

                    'speed_mhz' =>
                    $processorData['speed_mhz']
                        ?? null,

                    'architecture' =>
                    $processorData['architecture']
                        ?? null,

                    'family' =>
                    $processorData['family']
                        ?? null,

                    'generation' =>
                    $processorData['generation']
                        ?? null,

                ]
            );
        } else {

            $asset
                ->processors()
                ->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | RAM
        |--------------------------------------------------------------------------
        */

        $ramModules =
            $data['ram_modules']
            ?? [];


        $ramModules =
            $this->cleanCollection(
                $ramModules
            );


        /*
        | En un activo MANUAL podemos
        | reconstruir completamente los módulos.
        */

        $asset
            ->ramModules()
            ->delete();


        foreach (
            $ramModules as $ram
        ) {

            $asset->ramModules()->create([

                'manufacturer' =>
                $ram['manufacturer']
                    ?? null,

                'model' =>
                $ram['model']
                    ?? null,

                'serial_number' =>
                $ram['serial_number']
                    ?? null,

                'bank' =>
                $ram['bank']
                    ?? null,

                'slot' =>
                $ram['slot']
                    ?? null,

                'memory_type' =>
                $ram['memory_type']
                    ?? null,

                'capacity_gb' =>
                $ram['capacity_gb']
                    ?? null,

                'speed_mhz' =>
                $ram['speed_mhz']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        $storageDevices =
            $data['storage_devices']
            ?? [];


        $storageDevices =
            $this->cleanCollection(
                $storageDevices
            );


        $asset
            ->storageDevices()
            ->delete();


        foreach (
            $storageDevices as $storage
        ) {

            $asset->storageDevices()->create([

                'brand' =>
                $storage['brand']
                    ?? null,

                'model' =>
                $storage['model']
                    ?? null,

                'serial_number' =>
                $storage['serial_number']
                    ?? null,

                'firmware' =>
                $storage['firmware']
                    ?? null,

                'capacity_gb' =>
                $storage['capacity_gb']
                    ?? null,

                'free_gb' =>
                $storage['free_gb']
                    ?? null,

                'type' =>
                $storage['type']
                    ?? null,

                'health' =>
                $storage['health']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | GPU
        |--------------------------------------------------------------------------
        */

        $gpus =
            $data['gpus']
            ?? [];


        $gpus =
            $this->cleanCollection(
                $gpus
            );


        $asset
            ->gpus()
            ->delete();


        foreach (
            $gpus as $gpu
        ) {

            $asset->gpus()->create([

                'brand' =>
                $gpu['brand']
                    ?? null,

                'model' =>
                $gpu['model']
                    ?? null,

                'memory_mb' =>
                $gpu['memory_mb']
                    ?? null,

                'driver_version' =>
                $gpu['driver_version']
                    ?? null,

                'processor' =>
                $gpu['processor']
                    ?? null,

                'resolution' =>
                $gpu['resolution']
                    ?? null,

                'refresh_rate' =>
                $gpu['refresh_rate']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NETWORK
        |--------------------------------------------------------------------------
        */

        $network =
            $data['network']
            ?? [];


        if (
            $this->hasData(
                $network
            )
        ) {

            $assetNetwork =
                $asset->network()->updateOrCreate(

                    [
                        'asset_id' =>
                        $asset->id,
                    ],

                    [

                        'gateway' =>
                        $network['gateway']
                            ?? null,

                        'dns_server' =>
                        $network['dns_server']
                            ?? null,

                        'dhcp_enabled' =>
                        $network['dhcp_enabled']
                            ?? null,

                        'connected' =>
                        true,

                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | ADAPTADORES
            |--------------------------------------------------------------------------
            */

            $adapters =
                $network['adapters']
                ?? [];


            $adapters =
                $this->cleanCollection(
                    $adapters
                );


            $assetNetwork
                ->adapters()
                ->delete();


            foreach (
                $adapters as $adapter
            ) {

                $assetNetwork
                    ->adapters()
                    ->create([

                        'name' =>
                        $adapter['name']
                            ?? null,

                        'description' =>
                        $adapter['description']
                            ?? null,

                        'mac_address' =>
                        $adapter['mac_address']
                            ?? null,

                        'type' =>
                        $adapter['type']
                            ?? null,

                        'ipv4' =>
                        $adapter['ipv4']
                            ?? null,

                        'ipv6' =>
                        $adapter['ipv6']
                            ?? null,

                        'connected' =>
                        $adapter['connected']
                            ?? false,

                        'is_primary' =>
                        $adapter['is_primary']
                            ?? false,

                        'is_active' =>
                        $adapter['is_active']
                            ?? true,

                        'last_seen_at' =>
                        now(),

                    ]);
            }
        } else {

            /*
            | Si el equipo manual no tiene red,
            | eliminamos su configuración manual.
            */

            $asset
                ->network()
                ->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | BATERÍA
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Si el formulario está vacío:
        |
        | Desktop → no se crea batería.
        |
        */

        $battery =
            $data['battery']
            ?? [];


        if (
            $this->hasData(
                $battery
            )
        ) {

            /*
            | Sanitizamos runtime.
            |
            | No permitimos valores absurdos como
            | 71582788 minutos.
            */

            $runtime =
                $battery['estimated_runtime_minutes']
                ?? null;


            if (
                $runtime !== null &&
                (
                    !is_numeric($runtime) ||
                    $runtime < 0 ||
                    $runtime > 1440
                )
            ) {

                $runtime = null;
            }


            $asset->battery()->updateOrCreate(

                [
                    'asset_id' =>
                    $asset->id,
                ],

                [

                    'manufacturer' =>
                    $battery['manufacturer']
                        ?? null,

                    'model' =>
                    $battery['model']
                        ?? null,

                    'serial_number' =>
                    $battery['serial_number']
                        ?? null,

                    'health' =>
                    $battery['health']
                        ?? null,

                    'cycle_count' =>
                    $battery['cycle_count']
                        ?? null,

                    'design_capacity' =>
                    $battery['design_capacity']
                        ?? null,

                    'full_charge_capacity' =>
                    $battery['full_charge_capacity']
                        ?? null,

                    'remaining_capacity' =>
                    $battery['remaining_capacity']
                        ?? null,

                    'charge_percent' =>
                    $battery['charge_percent']
                        ?? null,

                    'is_charging' =>
                    $battery['is_charging']
                        ?? null,

                    'estimated_runtime_minutes' =>
                    $runtime,

                ]
            );
        } else {

            /*
            | Desktop o equipo sin batería.
            */

            $asset
                ->battery()
                ->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | MONITORES
        |--------------------------------------------------------------------------
        */

        $monitors =
            $data['monitors']
            ?? [];


        $monitors =
            $this->cleanCollection(
                $monitors
            );


        /*
        | Para activos manuales reconstruimos
        | la colección completa.
        */

        $asset
            ->monitors()
            ->delete();


        foreach (
            $monitors as $monitor
        ) {

            $asset->monitors()->create([

                'brand' =>
                $monitor['brand']
                    ?? null,

                'model' =>
                $monitor['model']
                    ?? null,

                'serial_number' =>
                $monitor['serial_number']
                    ?? null,

                'manufacturer_code' =>
                $monitor['manufacturer_code']
                    ?? null,

                'year' =>
                $monitor['year']
                    ?? null,

                'size' =>
                $monitor['size']
                    ?? null,

                'resolution' =>
                $monitor['resolution']
                    ?? null,

                'refresh_rate' =>
                $monitor['refresh_rate']
                    ?? null,

                'fixed_asset_code' =>
                $monitor['fixed_asset_code']
                    ?? null,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SOFTWARE
        |--------------------------------------------------------------------------
        |
        | Confirmado actualmente:
        | name + version.
        |
        */

        $software =
            $data['software']
            ?? [];


        $software =
            $this->cleanCollection(
                $software
            );


        $asset
            ->software()
            ->delete();


        foreach (
            $software as $program
        ) {

            if (
                empty(trim(
                    (string) (
                        $program['name']
                        ?? ''
                    )
                ))
            ) {
                continue;
            }


            $asset->software()->create([

                'name' =>
                strip_tags(
                    $program['name']
                ),

                'version' =>
                strip_tags(
                    $program['version']
                        ?? ''
                ),

            ]);
        }
    }


    /**
     * =========================================================
     * VERIFICAR SI UN ARREGLO TIENE INFORMACIÓN REAL
     * =========================================================
     */
    private function hasData(
        array $data
    ): bool {

        foreach (
            $data as $value
        ) {

            if (
                is_array($value)
            ) {

                if (
                    $this->hasData(
                        $value
                    )
                ) {

                    return true;
                }

                continue;
            }


            if (
                $value !== null &&
                $value !== ''
            ) {

                return true;
            }
        }


        return false;
    }


    /**
     * =========================================================
     * LIMPIAR COLECCIONES DE COMPONENTES
     * =========================================================
     */
    private function cleanCollection(
        array $items
    ): array {

        return array_values(
            array_filter(
                $items,
                function ($item) {

                    return is_array($item) &&
                        $this->hasData($item);
                }
            )
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
        'currentCustodian.dependency',
        'currentCustodian.jobTitle',
        'room.building',
        'technicalServices.user',
        'processors',
        'ramModules',
        'storageDevices',
        'gpus',
        'network.adapters',
        'monitors',
        'battery',
        'software',
    ]);

    return view(
        'admin.assets.pdf_preview',
        compact('asset')
    );
}


public function downloadPdf(int $id)
{
    $asset = Asset::with([
        'currentCustodian.dependency',
        'currentCustodian.jobTitle',
        'room.building',
        'technicalServices.user',
        'processors',
        'ramModules',
        'storageDevices',
        'gpus',
        'network.adapters',
        'monitors',
        'battery',
        'software',
    ])->findOrFail($id);

    $html = view(
        'admin.assets.pdf_export',
        compact('asset')
    )->render();

    $pdf = Browsershot::html($html)
        ->setNodeBinary('/usr/local/bin/node')
        ->addChromiumArguments([
            'no-sandbox',
            'disable-setuid-sandbox',
            'disable-dev-shm-usage',
            'disable-gpu',
            'no-zygote',
        ])
        ->showBackground()
        ->timeout(120)
        ->setOption('waitUntil', 'domcontentloaded')
        ->format('A4')
        ->setMargins(0, 0, 0, 0)
        ->pdf();

    $fileName = 'Hoja_Vida_USC_' .
        ($asset->internal_code ?? $asset->serial_number ?? $asset->id) .
        '.pdf';

    return response($pdf)
        ->header('Content-Type', 'application/pdf')
        ->header(
            'Content-Disposition',
            'attachment; filename="' . $fileName . '"'
        );
}
    /**
     * =========================================================
     * CONSULTAR DE FORMULARIO DE ACTIVO
     * =========================================================
     */
    public function show(Asset $asset)
    {
        $asset->load([
            'currentCustodian',
            'room.building',
            'assignments',
            'technicalServices',
            'history',
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'network.adapters',
            'monitors',
            'battery',
            'software',
        ]);

        return view('admin.assets.show', compact('asset'));
    }

    /**
     * =========================================================
     * ELIMINAR ACTIVO
     * =========================================================
     */
    public function destroy(
        Asset $asset
    ) {

        try {

            DB::transaction(
                function () use ($asset) {

                    $asset->delete();
                }
            );


            return redirect()
                ->route('assets.index')
                ->with(
                    'success',
                    'El activo ha sido dado de baja y eliminado del CMDB exitosamente.'
                );
        } catch (
            \Illuminate\Database\QueryException $e
        ) {

            Log::error(
                'Error de integridad al eliminar Asset',
                [

                    'asset_id' =>
                    $asset->id,

                    'error' =>
                    $e->getMessage(),

                ]
            );


            return redirect()
                ->route('assets.index')
                ->with(
                    'error',
                    'No se puede dar de baja el equipo porque tiene historiales de movimiento o mantenimientos asociados en el sistema.'
                );
        } catch (
            \Exception $e
        ) {

            Log::error(
                'Error al eliminar Asset',
                [

                    'asset_id' =>
                    $asset->id,

                    'error' =>
                    $e->getMessage(),

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
}
