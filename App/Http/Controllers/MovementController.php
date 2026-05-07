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
        $custodians = Custodian::all(); // Cargamos los responsables para el select
        return view('admin.movements.mass', compact('custodians'));
    }

    // Procesa el movimiento masivo
    public function storeMass(Request $request)
    {
        $request->validate([
            'serials' => 'required|string',
            'nomenclatura' => 'required|string|exists:rooms,nomenclatura',
            'custodian_id' => 'required|exists:custodians,id'
        ], [
            'nomenclatura.exists' => 'La nomenclatura ingresada no existe en SOMA.'
        ]);

        // Convertimos el texto del textarea en un array de seriales, limpiando espacios
        $serialsArray = explode("\n", str_replace("\r", "", $request->serials));
        $serialsArray = array_filter(array_map('trim', $serialsArray));

        // Buscamos la oficina destino
        $targetRoom = Room::where('nomenclatura', $request->nomenclatura)->first();
        $movedCount = 0;
        $notFound = [];

        foreach ($serialsArray as $serial) {
            $asset = Asset::where('serial_number', $serial)->first();

            if ($asset) {
                // 1. Cerrar la asignación vieja
                $asset->currentAssignment()?->update([
                    'status' => 'inactive',
                    'ended_at' => now()
                ]);

                // 2. Crear la nueva en el historial
                $asset->assignments()->create([
                    'custodian_id' => $request->custodian_id,
                    'room_id' => $targetRoom->id,
                    'started_at' => now(),
                    'status' => 'active',
                    'observations' => 'Movimiento masivo gestionado en SOMA'
                ]);

                // 3. Actualizar la ubicación actual del equipo
                $asset->update(['room_id' => $targetRoom->id]);
                $movedCount++;
            } else {
                $notFound[] = $serial;
            }
        }

        $mensaje = "$movedCount equipos trasladados correctamente a la oficina $targetRoom->nomenclatura.";
        if (count($notFound) > 0) {
            $mensaje .= " Ojo, no se encontraron estos seriales: " . implode(', ', $notFound);
        }

        return back()->with('success', $mensaje);
    }
}