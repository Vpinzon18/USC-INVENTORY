<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobTitle;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Capturamos la búsqueda y la paginación
    $search = $request->input('search');
    $perPage = $request->input('per_page', 5);

    // Consulta inteligente a la base de datos
    $jobtitles = \App\Models\JobTitle::query()
        ->when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })
        ->paginate($perPage)
        ->appends($request->query());

    // Retornamos a la vista dentro de la carpeta admin/jobtitles
    return view('admin.jobtitles.index', compact('jobtitles', 'search', 'perPage'));
}

    /**
     * Show the form for creating a new resource.
     */
   /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('admin.jobtitles.create');
    }

    /**
     * Guardar el recurso recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_titles,name', // Asegura que el cargo no exista ya
        ], [
            'name.required' => 'El nombre del cargo es obligatorio.',
            'name.unique' => 'Este cargo ya se encuentra registrado.'
        ]);

        // ESCUDO ANTI-XSS: Limpiamos el nombre del cargo
        $validated['name'] = strip_tags($validated['name']);

        // 2. Guardar en base de datos usando el array limpio
        \App\Models\JobTitle::create($validated);

        // 3. Redireccionar con mensaje de éxito
        return redirect()->route('jobtitles.index')
                         ->with('success', 'Cargo creado de forma segura.');
    }

    public function update(Request $request, string $id)
    {
        $jobtitle = \App\Models\JobTitle::findOrFail($id);

        // 1. Validar los datos (ignorando el ID actual para la regla unique)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_titles,name,' . $jobtitle->id,
        ], [
            'name.required' => 'El nombre del cargo es obligatorio.',
            'name.unique' => 'Este cargo ya se encuentra registrado.'
        ]);

        // ESCUDO ANTI-XSS: Limpiamos el nombre antes de actualizar
        $validated['name'] = strip_tags($validated['name']);

        // 2. Actualizar el registro con los datos limpios
        $jobtitle->update($validated);

        // 3. Redireccionar con mensaje
        return redirect()->route('jobtitles.index')
                         ->with('success', 'Cargo actualizado de forma segura.');
    }

    /**
     * Mostrar un recurso específico (Casi no se usa en catálogos simples)
     */
    public function show(string $id)
    {
        // Para este módulo no necesitamos vista 'show', así que redirigimos al index
        return redirect()->route('jobtitles.index');
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit(string $id)
    {
        // Busca el cargo o falla mostrando un error 404
        $jobtitle = \App\Models\JobTitle::findOrFail($id);
        
        return view('admin.jobtitles.edit', compact('jobtitle'));
    }

    /**
     * Actualizar el recurso especificado en la base de datos.
     */
    

    /**
     * Eliminar el recurso especificado de la base de datos.
     */
    public function destroy(string $id)
    {
        $jobtitle = \App\Models\JobTitle::findOrFail($id);
        $jobtitle->delete();

        return redirect()->route('jobtitles.index')
                         ->with('success', 'Cargo eliminado exitosamente.');
    }
}
