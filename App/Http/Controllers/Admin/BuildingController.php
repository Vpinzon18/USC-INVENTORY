<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
{
    $buildings = Building::with('campus')->get();
    
    return view('admin.buildings.index', compact('buildings'));
}
    public function create()
{
    $campuses = Campus::all(); 
    return view('admin.buildings.create', compact('campuses'));
}
    public function store(Request $request)
{
    $validated = $request->validate([
        'campus_id' => 'required|exists:campuses,id', 
        'name'      => 'required|string|max:100|unique:buildings,name', 
    ], [
        'campus_id.required' => 'Debe seleccionar una sede obligatoriamente.',
        'name.required'      => 'El nombre del bloque es necesario.',
        'name.unique'        => 'Ya existe un bloque con este nombre.',
    ]);

    Building::create($validated);

    return redirect()->route('buildings.index')
                     ->with('success', 'El bloque se ha creado correctamente.');
}
    public function show(string $id)
    {
        
    }
    public function edit(Building $building) {
    $campuses = Campus::all();
    return view('admin.buildings.edit', compact('building', 'campuses'));
}

public function update(Request $request, Building $building) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'campus_id' => 'required|exists:campuses,id',
    ]);
    $building->update($validated);
    return redirect()->route('buildings.index')->with('success', 'Bloque actualizado.');
}

public function destroy(Building $building) {
    $building->delete();
    return redirect()->route('buildings.index')->with('success', 'Bloque eliminado.');
}
}
