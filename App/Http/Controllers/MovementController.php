<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Custodian;
use App\Models\Assignment; // Asegúrate de importar esto
use function Spatie\LaravelPdf\Support\pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    public function storeMass(Request $request)
    {
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

        $actaNumber = 'R-AF001-' . date('Y') . '-' . str_pad(Auth::id(), 3, '0', STR_PAD_LEFT) . '-' . time();

        // 1. CAMBIO: Buscamos el modelo completo del Custodio usando su ID antes de la transacción.
        // Reemplaza 'Custodian' por el nombre real de tu modelo (ej. Custodio, User, etc.)
        $custodioModel = Custodian::findOrFail($custodianDestino);

        // Iniciamos la transacción
        // CAMBIO: Agregamos $custodioModel al "use" de la función anónima
        DB::transaction(function () use ($request, $custodianDestino, $custodioModel, $salaDestino, &$movedCount, &$ignoredCount, $actaNumber) {
            foreach ($request->selected_assets as $assetId) {
                $asset = Asset::find($assetId);
                if (!$asset) continue;

                $current = $asset->currentAssignment;

                // LÓGICA DE FILTRADO: Si el activo ya está en el destino, lo omitimos
                // Sigue funcionando igual porque $custodianDestino sigue siendo el ID
                if ($current && $current->custodian_id == $custodianDestino && $current->room_id == $salaDestino) {
                    $ignoredCount++;
                    continue;
                }

                // Si llega aquí, es porque el activo sí requiere movimiento
                if ($current) {
                    $current->update(['status' => 'inactive', 'ended_at' => now()]);
                }

                $asset->assignments()->create([
                    'custodian_id'  => $custodianDestino, // Mantiene el ID enviado

                    // 2. CAMBIO: Ahora le pedimos el cost_center al modelo que encontramos arriba
                    'cost_center'   => $custodioModel->cost_center,

                    'room_id'       => $salaDestino,
                    'started_at'    => now(),
                    'status'        => 'active',
                    'observations'  => $request->observation ?? 'Movimiento masivo gestionado en SOMA',
                    'movement_type' => $request->movement_type,
                    'headquarters'  => $request->headquarters,
                    'acta_number'   => $actaNumber,
                    'user_id'       => Auth::id(),
                ]);

                $asset->update(['room_id' => $salaDestino]);
                $movedCount++;
            }
        });

        // Validación final: si nada se movió (y nada se ignoró), es un error
        if ($movedCount === 0 && $ignoredCount === 0) {
            return back()->with('error', 'No se pudieron procesar los activos seleccionados.');
        }

        $mensaje = "¡Traslado exitoso! $movedCount equipos movidos.";
        if ($ignoredCount > 0) {
            $mensaje .= " (Se omitieron $ignoredCount equipos que ya estaban en el destino).";
        }

        if ($request->has('generate_pdf')) {
            return redirect()->route('movements.exportActa', ['actaNumber' => $actaNumber])
                ->with('success', $mensaje);
        }

        return redirect()->route('movements.mass.create')->with('success', $mensaje);
    }
    public function createMass()
    {
        $assets = Asset::with('room')->orderBy('serial_number')->get();
        $custodians = Custodian::with('rooms.building')->orderBy('full_name')->get();

        return view('admin.movements.mass', compact('custodians', 'assets'));
    }
    public function exportActa(string $actaNumber)
    {
        // 1. Obtener todas las asignaciones del acta actual
        $assignments = Assignment::with(['asset', 'custodian', 'room'])
            ->where('acta_number', $actaNumber)
            ->get();

        if ($assignments->isEmpty()) {
            return back()->with('error', 'Acta no encontrada.');
        }

        $assets = $assignments->pluck('asset');

        // 2. Responsable Actual (El que recibe - Lado derecho del PDF)
        $nuevoResponsable = $assignments->first()->custodian;
        $salaDestino = $assignments->first()->room;

        // 3. Responsable Anterior (El que entrega - Lado izquierdo del PDF)
        // Buscamos la última asignación que tenía el activo ANTES de este acta
        $primeraAsignacion = $assignments->first();
        $asignacionAnterior = Assignment::where('asset_id', $primeraAsignacion->asset_id)
            ->where('status', 'inactive')
            ->where('id', '<', $primeraAsignacion->id) // Asignación previa al ID actual
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
        // Buscamos equipos que ya tengan una asignación activa en el destino solicitado
        $conflicts = \App\Models\Asset::whereIn('id', $request->selected_assets)
            ->whereHas('currentAssignment', function ($query) use ($request) {
                $query->where('custodian_id', $request->custodian_id)
                    ->where('room_id', $request->room_id)
                    ->where('status', 'active');
            })
            ->pluck('internal_code'); // O 'serial_number'

        return response()->json([
            'has_conflict' => $conflicts->isNotEmpty(),
            'conflicts' => $conflicts
        ]);
    }
}
