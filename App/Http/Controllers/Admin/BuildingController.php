<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Usamos 'with' para traer también el nombre de la sede (evita el problema N+1)
    $buildings = Building::with('campus')->get();
    
    // Retornamos la vista y le pasamos los bloques
    return view('admin.buildings.index', compact('buildings'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    // Traemos todas las sedes para el Combobox
    $campuses = Campus::all(); 
    return view('admin.buildings.create', compact('campuses'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Validación: Aseguramos que los datos sean correctos
    $validated = $request->validate([
        'campus_id' => 'required|exists:campuses,id', // Debe existir en la tabla campuses
        'name'      => 'required|string|max:100|unique:buildings,name', // Nombre único para evitar duplicados
    ], [
        // Mensajes personalizados (opcional pero profesional)
        'campus_id.required' => 'Debe seleccionar una sede obligatoriamente.',
        'name.required'      => 'El nombre del bloque es necesario.',
        'name.unique'        => 'Ya existe un bloque con este nombre.',
    ]);

    // 2. Creación: Guardamos en la base de datos
    Building::create($validated);

    // 3. Redirección: Volvemos al listado con un mensaje de éxito
    return redirect()->route('buildings.index')
                     ->with('success', 'El bloque se ha creado correctamente.');
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
