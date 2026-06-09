<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;

class RoomController extends Controller
{
   public function index()
{
    $rooms = Room::with('building')->get(); 
    return view('admin.rooms.index', compact('rooms'));
}

public function create()
{
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
  
    public function show(string $id)
    {
       
    }
public function edit(Room $room)
{
    $buildings = Building::all();
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
