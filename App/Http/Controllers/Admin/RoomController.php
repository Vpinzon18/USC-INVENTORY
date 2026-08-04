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
        // 1. Traemos las relaciones necesarias (Eager Loading)
        $query = Room::with(['building.campus', 'type']);

        // 2. Filtros dinámicos recibidos por AJAX
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cambia 'ilike' por 'like' si estás usando MySQL
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('nomenclatura', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('campus_id')) {
            $query->whereHas('building', function($q) use ($request) {
                $q->where('campus_id', $request->campus_id);
            });
        }

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        // 3. Paginación
        $perPage = $request->input('per_page', 15);
        $rooms = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        // 🌟 LA MAGIA DEL AJAX: Si la petición viene de AlpineJS, devolvemos SOLO la tabla
        if ($request->ajax()) {
            return view('admin.rooms.partials.table', compact('rooms'))->render();
        }

        // Si es una carga normal, devolvemos la vista completa
        return view('admin.rooms.index', compact('rooms'));
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