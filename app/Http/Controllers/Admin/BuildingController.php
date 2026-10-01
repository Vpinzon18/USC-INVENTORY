<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
{
    // Iniciamos la consulta base
    $query = \App\Models\Building::with('campus'); // O el namespace de tu modelo

    // 1. Filtro de Búsqueda por Texto
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('name', 'ilike', "%{$search}%"); 
    }

    // 2. 🌟 ESTE ES EL FILTRO DE SEDES QUE TE FALTA 🌟
    if ($request->filled('campus_id')) {
        $query->where('campus_id', $request->campus_id);
    }

    // 3. 🌟 ESTO RECIBE LA CANTIDAD DE LA PAGINACIÓN 🌟
    $perPage = $request->input('per_page', 15);

    // Ejecutamos la consulta y paginamos conservando los filtros (withQueryString)
    $buildings = $query->orderBy('name', 'asc')
                       ->paginate($perPage)
                       ->withQueryString();

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

    if (isset($validated['name'])) {
            $validated['name'] = strip_tags($validated['name']);
        }

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

public function update(Request $request, Building $building) 
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
        ]);

        // ESCUDO ANTI-XSS
        if (isset($validated['name'])) {
            $validated['name'] = strip_tags($validated['name']);
        }

        $building->update($validated);

        return redirect()->route('buildings.index')->with('success', 'Bloque actualizado.');
    }

public function destroy(Building $building) {
    $building->delete();
    return redirect()->route('buildings.index')->with('success', 'Bloque eliminado.');
}
}
