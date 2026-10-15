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
        $filters = $request->only(['search', 'campus_id', 'per_page', 'page']);
        $search = trim((string) ($filters['search'] ?? ''));
        $campusId = filter_var(
            $filters['campus_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        ) ?: null;

        $query = Building::with('campus');

        if ($search !== '') {
            $query->where('name', 'ilike', "%{$search}%");
        }

        if ($campusId) {
            $query->where('campus_id', $campusId);
        }

        $perPage = \App\Support\PaginationOptions::fromRequest($request);
        $buildings = $query->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.buildings.index', compact('buildings', 'filters'));
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
