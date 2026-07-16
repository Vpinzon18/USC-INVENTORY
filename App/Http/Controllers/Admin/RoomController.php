<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;
use App\Models\RoomType; // 🌟 IMPORTANTE: Importamos tu nuevo modelo relacional

class RoomController extends Controller
{
    /**
     * Muestra el listado de Oficinas con soporte para filtros AJAX y paginación masiva.
     */
    public function index(Request $request)
    {
        // Optimizamos la consulta cargando relaciones en cascada (Eager Loading)
        $query = Room::with('building.campus');

        // 1. Filtro de Búsqueda General (Nombre o Nomenclatura)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Usamos 'ilike' para PostgreSQL (sensible a mayúsculas/minúsculas de forma segura)
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('nomenclatura', 'ilike', "%{$search}%");
            });
        }

        // 2. Filtro por Piso
        if ($request->filled('floor')) {
            $query->where('floor', 'ilike', "%{$request->floor}%");
        }

        // Configuración de la paginación dinámica conectada con la barra del Design System
        $perPage = $request->input('per_page', 15);
        $rooms = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Carga el formulario de creación inyectando los bloques y tipos de espacio activos.
     */
    public function create()
    {
        // Traemos bloques ordenados con sus sedes para el buscador inteligente
        $buildings = Building::with('campus')->orderBy('name')->get();
        
        // 🌟 Inyectamos solo los Tipos de Espacio ACTIVOS creados en tu nuevo módulo
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.rooms.create', compact('buildings', 'roomTypes'));
    }

    /**
     * Almacena una nueva ubicación validando la integridad referencial y protegiendo contra XSS.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'nomenclatura' => 'nullable|string|unique:rooms,nomenclatura', 
            'building_id'  => 'required|exists:buildings,id',
            'floor'        => 'required|string',               // Cambiado a string para aceptar textos
            'room_type_id' => 'required|exists:room_types,id', // 🌟 Valida que el Tipo exista en la nueva tabla
            'status'       => 'required|string',               // 🌟 Valida el nuevo campo de estado operativo
        ]);

        // ESCUDO ANTI-XSS DINÁMICO
        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Oficina creada de forma segura.');
    }
  
    /**
     * Espacio reservado para ver detalles específicos.
     */
    public function show(string $id)
    {
       // Implementación futura si se requiere auditoría visual
    }

    /**
     * Carga el formulario de edición manteniendo las relaciones necesarias para el buscador local AlpineJS.
     */
    public function edit(Room $room)
    {
        // Necesitamos with('campus') para alimentar el mapeo geográfico interactivo
        $buildings = Building::with('campus')->orderBy('name')->get();
        
        // Cargamos los tipos de espacio dinámicos para reasociar el ComboBox
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.rooms.edit', compact('room', 'buildings', 'roomTypes'));
    }

    /**
     * Actualiza el registro de la oficina mitigando vulnerabilidades.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'nomenclatura' => 'nullable|string|unique:rooms,nomenclatura,' . $room->id,
            'building_id'  => 'required|exists:buildings,id',
            'floor'        => 'required|string',               // Sólido como string corporativo
            'room_type_id' => 'required|exists:room_types,id', // 🌟 Sincronizado
            'status'       => 'required|string',               // 🌟 Sincronizado
        ]);

        // ESCUDO ANTI-XSS DINÁMICO
        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Oficina actualizada de forma segura.');
    }

    /**
     * Remueve la oficina aplicando capas extras de control referencial.
     */
    public function destroy(Room $room)
    {
        // Validación relacional preventiva en backend para proteger el inventario
        if (method_exists($room, 'assets') && $room->assets()->count() > 0) {
            return redirect()->back()->withErrors(['No se puede eliminar la oficina porque tiene activos asignados actualmente en SIGMA.']);
        }

        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Oficina eliminada con éxito.');
    }
}