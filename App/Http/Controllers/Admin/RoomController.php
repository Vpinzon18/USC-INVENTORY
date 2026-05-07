<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    // 1. Recuperamos las oficinas (usando 'with' para traer el bloque asociado)
    $rooms = Room::with('building')->get(); 

    // 2. IMPORTANTE: Debes pasar la variable a la vista
    // Asegúrate de que el nombre en compact('rooms') coincida con la vista
    return view('admin.rooms.index', compact('rooms'));
}

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    // Cargamos los bloques con sus sedes para que el usuario sepa bien qué está eligiendo
    $buildings = Building::with('campus')->get();
    return view('admin.rooms.create', compact('buildings'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nomenclatura' => 'nullable|string|unique:rooms,nomenclatura', // Validación
        'building_id' => 'required|exists:buildings,id',
        'floor' => 'required|integer',
    ]);

    Room::create($validated);

    return redirect()->route('rooms.index')->with('success', 'Oficina creada correctamente.');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // app/Http/Controllers/RoomController.php

public function edit(Room $room)
{
    $buildings = Building::all(); // Necesario para el select de bloques
    return view('admin.rooms.edit', compact('room', 'buildings'));
}

public function update(Request $request, Room $room)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nomenclatura' => 'nullable|string|unique:rooms,nomenclatura,' . $room->id,
        'building_id' => 'required|exists:buildings,id',
        'floor' => 'required|integer',
    ]);

    $room->update($validated);

    return redirect()->route('rooms.index')->with('success', 'Oficina actualizada correctamente.');
}

public function destroy(Room $room)
{
    $room->delete();
    return redirect()->route('rooms.index')->with('success', 'Oficina eliminada con éxito.');
}
}
