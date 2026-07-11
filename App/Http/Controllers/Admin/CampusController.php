<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * Muestra el listado de Sedes con filtros y paginación dinámica.
     */
    public function index(Request $request)
    {
        $query = Campus::query();

        // Filtro de Búsqueda General (Solo por Nombre)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'ilike', "%{$search}%"); // Si usas MySQL cambia 'ilike' por 'like'
        }

        // Paginación Dinámica
        $perPage = $request->input('per_page', 15);

        // Ejecutamos la consulta
        $campuses = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        // 🌟 CÁLCULO DE KPIs CORREGIDO 🌟
        // Ya NO buscamos 'status' en la tabla assets.
        $kpis = [
            // Cuenta absolutamente todos los equipos en la tabla
            'registrados'   => \App\Models\Asset::count(),
            
            // Asumimos que un equipo está asignado si tiene un room_id (Salón/Oficina)
            'asignados'     => \App\Models\Asset::whereNotNull('room_id')->count(),
            
            // Ponemos 0 temporalmente en estos para evitar errores de SQL
            'mantenimiento' => 0,
            'bajas'         => 0,
        ];

        return view('admin.campuses.index', compact('campuses', 'kpis'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('admin.campuses.create');
    }

    /**
     * Guarda la nueva Sede en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:campuses,name',
            'address' => 'nullable|string|max:255',
            'status'  => 'required|string|in:Activa,Inactiva',
        ], [
            'name.unique' => 'Ya existe una sede o convenio registrado con este nombre.'
        ]);

        Campus::create($validated);

        return redirect()->route('campuses.index')
                         ->with('success', 'Sede registrada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una Sede existente.
     */
    public function edit(Campus $campus)
    {
        return view('admin.campuses.edit', compact('campus'));
    }

    /**
     * Actualiza la Sede en la base de datos.
     */
    public function update(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            // Ignoramos el ID actual para que no marque error consigo mismo
            'name'    => 'required|string|max:255|unique:campuses,name,' . $campus->id,
            'address' => 'nullable|string|max:255',
            'status'  => 'required|string|in:Activa,Inactiva',
        ], [
            'name.unique' => 'Ya existe otra sede registrada con este nombre.'
        ]);

        $campus->update($validated);

        return redirect()->route('campuses.index')
                         ->with('success', 'Información de la sede actualizada correctamente.');
    }

    /**
     * Elimina la Sede
     */
    public function destroy(Campus $campus)
    {
        // Protección extra de Backend
        $hasRelations = $campus->buildings()->count() > 0 || (method_exists($campus, 'rooms') && $campus->rooms()->count() > 0);
        
        if ($hasRelations) {
            return back()->with('error', 'La sede no puede ser eliminada porque tiene bloques u oficinas asociadas.');
        }

        $campus->delete();

        return redirect()->route('campuses.index')
                         ->with('success', 'Sede eliminada del sistema.');
    }
}