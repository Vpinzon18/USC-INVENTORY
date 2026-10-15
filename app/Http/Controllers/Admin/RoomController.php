<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;
use App\Models\RoomType;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'campus_id', 'building_id', 'room_type_id', 'per_page', 'page']);
        $search = trim((string) ($filters['search'] ?? ''));
        $campusId = filter_var($filters['campus_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
        $buildingId = filter_var($filters['building_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
        $roomTypeId = filter_var($filters['room_type_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
        // 1. Traemos las relaciones necesarias (Eager Loading)
        $query = Room::with(['building.campus', 'type']);

        // 2. Filtros dinámicos recibidos por AJAX
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                // Cambia 'ilike' por 'like' si estás usando MySQL
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('nomenclatura', 'ilike', "%{$search}%");
            });
        }

        if ($campusId) {
            $query->whereHas('building', function ($q) use ($campusId) {
                $q->where('campus_id', $campusId);
            });
        }

        if ($buildingId) {
            $query->where('building_id', $buildingId);
        }

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        // 3. Paginación
        $perPage = \App\Support\PaginationOptions::fromRequest($request);
        $rooms = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        // 🌟 LA MAGIA DEL AJAX: Si la petición viene de AlpineJS, devolvemos SOLO la tabla
        if ($request->ajax()) {
            return view('admin.rooms.partials.table', compact('rooms', 'filters'))->render();
        }

        // Si es una carga normal, devolvemos la vista completa
        return view('admin.rooms.index', compact('rooms', 'filters'));
    }

    public function create()
    {
        $buildings = Building::with('campus')->orderBy('name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.rooms.create', compact('buildings', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'nomenclatura' => 'nullable|string|unique:rooms,nomenclatura', 
            'building_id'  => 'required|exists:buildings,id',
            'floor'        => 'required|string', 
            'room_type_id' => 'required|exists:room_types,id',
            'status'       => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        Room::create($validated);

        // 🌟 CORREGIDO: Redirección apuntando a 'rooms.index' (sin el admin.)
        return redirect()->route('rooms.index')->with('success', 'Oficina creada de forma segura.');
    }
  
    public function show(string $id)
    {
       //
    }

    public function edit(Room $room)
    {
        $buildings = Building::with('campus')->orderBy('name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.rooms.edit', compact('room', 'buildings', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'nomenclatura' => 'nullable|string|unique:rooms,nomenclatura,' . $room->id,
            'building_id'  => 'required|exists:buildings,id',
            'floor'        => 'required|string',
            'room_type_id' => 'required|exists:room_types,id',
            'status'       => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        $room->update($validated);

        // 🌟 CORREGIDO: Redirección apuntando a 'rooms.index'
        return redirect()->route('rooms.index')->with('success', 'Oficina actualizada de forma segura.');
    }

    public function destroy(Room $room)
    {
        if (method_exists($room, 'assets') && $room->assets()->count() > 0) {
            return redirect()->back()->withErrors(['No se puede eliminar la oficina porque tiene activos asignados actualmente en SIGMA.']);
        }

        $room->delete();
        
        // 🌟 CORREGIDO: Redirección apuntando a 'rooms.index'
        return redirect()->route('rooms.index')->with('success', 'Oficina eliminada con éxito.');
    }
}