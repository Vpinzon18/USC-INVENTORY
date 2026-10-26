<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Campus;
use App\Models\Custodian;
use App\Models\Room;
use Illuminate\Http\Request;

class AssetIndexController extends Controller
{
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

                $query->whereRaw(
                    'COALESCE(agent_last_seen, last_seen_at) >= ?',
                    [now()->subMinutes(Asset::ONLINE_THRESHOLD_MINUTES)]
                );
            } else {

                $query->where(
                    function ($q) {
                        $limit = now()->subMinutes(Asset::ONLINE_THRESHOLD_MINUTES);
                        $q->whereRaw(
                            '(COALESCE(agent_last_seen, last_seen_at) < ? OR COALESCE(agent_last_seen, last_seen_at) IS NULL)',
                            [$limit]
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

        $online = Asset::whereRaw(
            'COALESCE(agent_last_seen, last_seen_at) >= ?',
            [now()->subMinutes(Asset::ONLINE_THRESHOLD_MINUTES)]
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



}
