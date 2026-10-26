<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assets\StoreAssetRequest;
use App\Http\Requests\Assets\UpdateAssetRequest;

use App\Models\Asset;
use App\Models\Room;
use App\Models\Campus;
use App\Models\Custodian;
use App\Models\Assignment;
use App\Models\AssetMonitor;
use App\Services\Inventory\ManualComponentSynchronizer;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AssetController extends Controller
{
 
    //  LISTADO DE ACTIVOS
     
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
    public function store(StoreAssetRequest $request)
    {
        $validated = $request->validated();


        DB::transaction(
            function () use ($validated) {

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

                app(ManualComponentSynchronizer::class)->syncManualComponents(
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
        UpdateAssetRequest $request,
        Asset $asset
    ) {

        $agentManaged =
            (bool) $asset->is_agent_managed;


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $validated = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use ($asset, $validated, $request, $agentManaged) {

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

                    app(ManualComponentSynchronizer::class)->syncManualComponents(
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

                        /** @var \App\Models\Software|null $software */
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

                    /** @var Assignment|null $activeAssignment */
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
