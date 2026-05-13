<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Custodian;

class MovementController extends Controller
{
    // Muestra el formulario
    public function createMass()
{
    // 1. Cargamos los equipos con su ubicación actual (Eager Loading para que sea veloz)
    $assets = Asset::with('room')->orderBy('serial_number')->get();

    // 2. Cargamos los responsables con sus ubicaciones para el selector dinámico
    $custodians = Custodian::with('rooms.building')->orderBy('full_name')->get();

    // 3. Pasamos ambas colecciones a la vista
    return view('admin.movements.mass', compact('custodians', 'assets'));
}

    // Procesa el movimiento masivo
    public function storeMass(Request $request)
{
    // 1. Cambiamos la validación para recibir el array 'selected_assets' y el 'room_id'
    $request->validate([
        'selected_assets' => 'required|array|min:1',
        'selected_assets.*' => 'exists:assets,id',
        'room_id' => 'required|exists:rooms,id',
        'custodian_id' => 'required|exists:custodians,id'
    ], [
        'selected_assets.required' => 'Debe seleccionar al menos un equipo de la lista.',
        'room_id.required' => 'Debe seleccionar una oficina de destino.'
    ]);

    // 2. Buscamos la oficina destino directamente por ID (ya no por texto)
    $targetRoom = Room::findOrFail($request->room_id);
    $movedCount = 0;

    // 3. Procesamos los IDs de los equipos seleccionados
    foreach ($request->selected_assets as $assetId) {
        $asset = Asset::find($assetId);

        if ($asset) {
            // A. Cerrar la asignación vieja (Historial)
            // Nota: Asegúrate de tener definido currentAssignment() en tu modelo Asset
            $asset->currentAssignment()?->update([
                'status' => 'inactive',
                'ended_at' => now()
            ]);

            // B. Crear la nueva en el historial
            $asset->assignments()->create([
                'custodian_id' => $request->custodian_id,
                'room_id' => $targetRoom->id,
                'started_at' => now(),
                'status' => 'active',
                'observations' => $request->observation ?? 'Movimiento masivo gestionado en SOMA'
            ]);

            // C. Actualizar la ubicación actual del equipo en la tabla assets
            $asset->update(['room_id' => $targetRoom->id]);
            $movedCount++;
        }
    }

    return redirect()->route('custodians.index') // O a la ruta que prefieras
        ->with('success', "$movedCount equipos han sido trasladados exitosamente a la ubicación $targetRoom->nomenclatura.");
}
    public function createMassiveMovement()
{
    $assets = Asset::with('room')->orderBy('serial_number')->get();
    $custodians = Custodian::with('rooms.building')->get();
    
    return view('admin.movements.massive', compact('assets', 'custodians'));
}
}