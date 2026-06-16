<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Custodian;
use App\Models\Assignment;
use function Spatie\LaravelPdf\Support\pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    /**
     * Historial de Movimientos (Index)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Agregamos 'user' al ->with() para cargar la relación
        $movements = \App\Models\Assignment::with(['custodian', 'user'])
            ->select(
                'acta_number',
                'movement_type',
                'custodian_id',
                'user_id', // <-- NUEVO: Traemos el ID del técnico
                DB::raw('MAX(created_at) as created_at'),
                DB::raw('MIN(id) as id'),
                DB::raw('COUNT(asset_id) as total_equipos')
            )
            ->when($search, function ($query, $search) {
                return $query->where('acta_number', 'LIKE', "%{$search}%")
                             ->orWhere('movement_type', 'LIKE', "%{$search}%")
                             ->orWhereHas('custodian', function ($q) use ($search) {
                                 $q->where('full_name', 'LIKE', "%{$search}%");
                             })
                             // Opcional: Permitir buscar también por el nombre del técnico
                             ->orWhereHas('user', function ($q) use ($search) {
                                 $q->where('name', 'LIKE', "%{$search}%");
                             });
            })
            // NUEVO: Añadimos 'user_id' a la regla de agrupación
            ->groupBy('acta_number', 'movement_type', 'custodian_id', 'user_id') 
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.movements.index', compact('movements', 'search', 'perPage'));
    }
    public function edit(string $id)
    {
        // 1. Buscamos el movimiento base para obtener el número de acta
        $baseMovement = \App\Models\Assignment::findOrFail($id);
        
        // 2. Traemos todos los equipos que comparten esa acta y fecha
        $batchMovements = \App\Models\Assignment::with(['asset', 'custodian'])
            ->where('acta_number', $baseMovement->acta_number)
            ->get();

        // 3. Catálogos para los selectores
        $custodians = \App\Models\Custodian::orderBy('full_name', 'asc')->get();
        $assets = \App\Models\Asset::orderBy('serial_number', 'asc')->get();

        return view('admin.movements.edit', compact('baseMovement', 'batchMovements', 'custodians', 'assets'));
    }

    public function update(Request $request, string $id)
    {
        $baseMovement = \App\Models\Assignment::findOrFail($id);

        $validated = $request->validate([
            'movement_type' => 'required|string',
            'custodian_id'  => 'required|exists:custodians,id',
            'observations'  => 'nullable|string',
            'asset_ids'     => 'required|array|min:1', // El array de equipos que se quedan/añaden
            'asset_ids.*'   => 'exists:assets,id',
        ]);

        // Convertimos los IDs enviados a enteros para comparar con seguridad
        $newAssetIds = array_map('intval', $request->asset_ids);

        // 1. OBTENER ESTADO ACTUAL: Equipos que están registrados hoy en esa acta
        $currentAssetIds = \App\Models\Assignment::where('acta_number', $baseMovement->acta_number)
            ->pluck('asset_id')
            ->toArray();

        // 2. EQUIPOS A ELIMINAR (Están en la BD pero el usuario los quitó en la vista)
        $assetsToRemove = array_diff($currentAssetIds, $newAssetIds);
        if (!empty($assetsToRemove)) {
            \App\Models\Assignment::where('acta_number', $baseMovement->acta_number)
                ->whereIn('asset_id', $assetsToRemove)
                ->delete();
        }

       // 3. ACTUALIZAR EXISTENTES Y CREAR NUEVOS
        foreach ($newAssetIds as $assetId) {
            \App\Models\Assignment::updateOrCreate(
                [
                    'acta_number' => $baseMovement->acta_number,
                    'asset_id'    => $assetId
                ],
                [
                    'movement_type' => $request->movement_type,
                    'custodian_id'  => $request->custodian_id,
                    'observations'  => $request->observations,
                    'room_id'       => $baseMovement->room_id,
                    'started_at'    => $baseMovement->started_at ?? now(),
                    'status'        => $baseMovement->status ?? 'active',
                    'user_id'       => Auth::user()->id,
                ]
            );
        }

        return redirect()->route('movements.index')
                         ->with('success', 'El lote del acta ' . $baseMovement->acta_number . ' ha sido reestructurado y actualizado con éxito.');
    }
    public function storeMass(Request $request)
{
    // Validamos únicamente los parámetros del formulario de SIGMA
    $request->validate([
        'selected_assets' => 'required|array|min:1',
        'selected_assets.*' => 'exists:assets,id',
        'room_id' => 'required|exists:rooms,id',
        'custodian_id' => 'required|exists:custodians,id',
        'movement_type' => 'required|string',
        'headquarters' => 'required|string',
    ]);

    $custodianDestino = $request->custodian_id;
    $salaDestino = $request->room_id;
    $movedCount = 0;
    $ignoredCount = 0;

    // Generación del consecutivo de Acta de SIGMA
    $actaNumber = 'R-AF001-' . date('Y') . '-' . str_pad(Auth::id(), 3, '0', STR_PAD_LEFT) . '-' . time();
    $custodioModel = Custodian::findOrFail($custodianDestino);

    // Evaluamos si el soporte TI requiere la cláusula de "Relación de Equipos" (> 5)
    $totalActivos = count($request->selected_assets);
    $observacionesFinales = $request->observation ?? 'Movimiento masivo gestionado en SIGMA';

    if ($totalActivos > 5) {
        $observacionesFinales .= "\n\n[SOPORTE TI: Debido al volumen del movimiento (>5 equipos), SIGMA generó automáticamente la relación detallada de hardware en el anexo del Acta Técnica].";
    }

    DB::transaction(function () use ($request, $custodianDestino, $custodioModel, $salaDestino, &$movedCount, &$ignoredCount, $actaNumber, $observacionesFinales) {
        foreach ($request->selected_assets as $assetId) {
            $asset = Asset::find($assetId);
            if (!$asset) continue;

            $current = $asset->currentAssignment;

            // Omitir si ya está asignado al mismo destino
            if ($current && $current->custodian_id == $custodianDestino && $current->room_id == $salaDestino) {
                $ignoredCount++;
                continue;
            }

            // Desactivar asignación previa
            if ($current) {
                $current->update(['status' => 'inactive', 'ended_at' => now()]);
            }

            // Registrar nueva asignación en lote
            $asset->assignments()->create([
                'custodian_id'  => $custodianDestino,
                'cost_center'   => $custodioModel->cost_center,
                'room_id'       => $salaDestino,
                'started_at'    => now(),
                'status'        => 'active',
                'observations'  => $observacionesFinales,
                'movement_type' => $request->movement_type,
                'headquarters'  => $request->headquarters,
                'acta_number'   => $actaNumber,
                'user_id'       => Auth::id(),
                // excel_relation_path ya no es necesario ya que el PDF se genera dinámicamente con la relación
            ]);

            // Actualizar ubicación física del hardware
            $asset->update(['room_id' => $salaDestino]);
            $movedCount++;
        }
    });

    if ($movedCount === 0 && $ignoredCount === 0) {
        return back()->with('error', 'No se pudieron procesar los activos seleccionados.');
    }

    $mensaje = "¡Traslado exitoso! $movedCount equipos procesados por SIGMA.";
    if ($ignoredCount > 0) {
        $mensaje .= " (Se omitieron $ignoredCount equipos con ubicación destino idéntica).";
    }

    // El sistema redirige automáticamente al generador de PDF usando el acta única compartida por el lote
    if ($request->has('generate_pdf')) {
        return redirect()->route('movements.exportActa', ['actaNumber' => $actaNumber])
            ->with('success', $mensaje);
    }

    return redirect()->route('movements.mass.create')->with('success', $mensaje);
}
    
    public function createMass()
    {
        $assets = Asset::with(['room', 'currentAssignment'])->orderBy('serial_number')->take(300)->get();
        
        $custodians = Custodian::with('rooms.building')->orderBy('full_name')->get();

        return view('admin.movements.mass', compact('custodians', 'assets'));
    }
    
    public function exportActa(string $actaNumber)
    {
        $assignments = Assignment::with(['asset', 'custodian', 'room'])
            ->where('acta_number', $actaNumber)
            ->get();

        if ($assignments->isEmpty()) {
            return back()->with('error', 'Acta no encontrada.');
        }

        $assets = $assignments->pluck('asset');

        $nuevoResponsable = $assignments->first()->custodian;
        $salaDestino = $assignments->first()->room;

        $primeraAsignacion = $assignments->first();
        $asignacionAnterior = Assignment::where('asset_id', $primeraAsignacion->asset_id)
            ->where('status', 'inactive')
            ->where('id', '<', $primeraAsignacion->id)
            ->orderBy('ended_at', 'desc')
            ->with(['custodian', 'room'])
            ->first();

        $responsableAnterior = $asignacionAnterior ? $asignacionAnterior->custodian : null;
        $salaOrigen = $asignacionAnterior ? $asignacionAnterior->room : null;

        return pdf()
            ->view('admin.movements.acta_entrega', compact(
                'assets',
                'nuevoResponsable',
                'salaDestino',
                'responsableAnterior',
                'salaOrigen',
                'actaNumber'
            ))
            ->format('letter')
            ->download("Acta_{$actaNumber}.pdf");
    }
    
    public function validateConflict(Request $request)
    {

        $conflicts = \App\Models\Asset::whereIn('id', $request->selected_assets)
            ->whereHas('currentAssignment', function ($query) use ($request) {
                $query->where('custodian_id', $request->custodian_id)
                    ->where('room_id', $request->room_id)
                    ->where('status', 'active');
            })
            ->pluck('internal_code'); 

        return response()->json([
            'has_conflict' => $conflicts->isNotEmpty(),
            'conflicts' => $conflicts
        ]);
    }
    
}