<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Muestra el listado de Tipos de Espacio.
     */
    public function index(Request $request)
    {
        // Traemos los tipos y contamos cuántas oficinas tienen asignadas para evitar su eliminación si están en uso
        $query = RoomType::withCount('rooms');

        // Filtro de Búsqueda General
        if ($request->filled('search')) {
            $search = $request->search;
            // Usa 'like' si estás en MySQL, 'ilike' en PostgreSQL
            $query->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
        }

        // Paginación Dinámica
        $perPage = $request->input('per_page', 15);
        $roomTypes = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        return view('admin.room_types.index', compact('roomTypes'));
    }

    /**
     * Muestra el formulario para crear un nuevo Tipo de Espacio.
     */
    public function create()
    {
        return view('admin.room_types.create');
    }

    /**
     * Almacena un nuevo Tipo de Espacio en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'El nombre del tipo de espacio es obligatorio.',
            'name.unique' => 'Ya existe un tipo de espacio con este nombre.',
        ]);

        RoomType::create($request->all());

        return redirect()->route('admin.room_types.index')->with('success', 'Tipo de espacio registrado correctamente.');
    }

    /**
     * Muestra el formulario para editar un Tipo de Espacio existente.
     */
    public function edit(RoomType $roomType)
    {
        return view('admin.room_types.edit', compact('roomType'));
    }

    /**
     * Actualiza un Tipo de Espacio en la base de datos.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name,' . $roomType->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'El nombre del tipo de espacio es obligatorio.',
            'name.unique' => 'Ya existe un tipo de espacio con este nombre.',
        ]);

        $roomType->update($request->all());

        return redirect()->route('admin.room_types.index')->with('success', 'Tipo de espacio actualizado correctamente.');
    }

    /**
     * Elimina un Tipo de Espacio de la base de datos.
     */
    public function destroy(RoomType $roomType)
    {
        // Verificación extra de seguridad (aunque se controla en frontend y DB)
        if ($roomType->rooms()->count() > 0) {
            return redirect()->back()->withErrors(['No se puede eliminar este tipo de espacio porque tiene oficinas asignadas.']);
        }

        $roomType->delete();

        return redirect()->route('admin.room_types.index')->with('success', 'Tipo de espacio eliminado correctamente.');
    }
}