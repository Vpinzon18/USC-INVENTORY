<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dependency;
use Illuminate\Http\Request;

class DependencyController extends Controller
{
    // Mostrar la lista completa
    public function index(Request $request)
{
    // 1. Capturamos lo que el usuario quiere buscar (si no hay nada, queda vacío)
    $search = $request->input('search');
    
    // 2. Capturamos cuántos registros quiere ver (por defecto mostrará 5)
    $perPage = $request->input('per_page', 5);

    // 3. Armamos la consulta a la base de datos
    $dependencies = Dependency::query()
        ->when($search, function ($query, $search) {
            // Busca coincidencias en el nombre
            return $query->where('name', 'LIKE', "%{$search}%");
        })
        ->paginate($perPage)
        ->appends($request->query()); // Mantiene la búsqueda al cambiar de página

    return view('admin.dependencies.index', compact('dependencies', 'search', 'perPage'));
}
    // Mostrar el formulario para crear
    public function create()
    {
        return view('admin.dependencies.create');
    }

    // Guardar en la base de datos
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:dependencies|max:255']);
        Dependency::create(['name' => $request->name]);

        return redirect()->route('dependencies.index')->with('success', 'Dependencia creada.');
    }

    // Mostrar el formulario para editar
    public function edit(Dependency $dependency)
    {
        return view('admin.dependencies.edit', compact('dependency'));
    }

    // Actualizar en la base de datos
    public function update(Request $request, Dependency $dependency)
    {
        // Nota clave: Agregamos el ID al final de la regla 'unique' para que 
        // Laravel no marque error si guardamos sin cambiar el nombre original.
        $request->validate([
            'name' => 'required|max:255|unique:dependencies,name,' . $dependency->id
        ]);

        $dependency->update([
            'name' => $request->name
        ]);

        return redirect()->route('dependencies.index')->with('success', 'Dependencia actualizada correctamente.');
    }

    // Eliminar de la base de datos
    public function destroy(Dependency $dependency)
    {
        // Precaución: Si esta dependencia ya tiene responsables asignados, 
        // la base de datos podría bloquear la eliminación por la llave foránea.
        $dependency->delete();

        return redirect()->route('dependencies.index')->with('success', 'Dependencia eliminada.');
    }
}
