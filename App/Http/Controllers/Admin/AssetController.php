<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Custodian;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $assets = Asset::with(['room', 'currentCustodian'])
            ->when($search, function ($query, $search) {
                return $query->where('serial_number', 'LIKE', "%{$search}%")
                             ->orWhere('hostname', 'LIKE', "%{$search}%")
                             ->orWhere('internal_code', 'LIKE', "%{$search}%");
            })
            ->paginate(15);

        return view('admin.assets.index', compact('assets', 'search'));
    }

    public function create()
    {
        $rooms = Room::all();
        $custodians = Custodian::all();
        return view('admin.assets.create', compact('rooms', 'custodians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'       => 'required|exists:rooms,id',
            'serial_number' => 'required|unique:assets,serial_number',
            'hostname'      => 'nullable|string',
            'ip_address'    => 'nullable|ip',
            'cpu'           => 'nullable|string',
            'ram'           => 'nullable|string',
            'storage'       => 'nullable|string',
            'custodian_id'  => 'required|exists:custodians,id', // Para crear la asignación inicial
        ]);

        // 1. Creamos el equipo
        $asset = Asset::create($validated);

        // 2. Creamos el registro en el historial (Assignment)
        Assignment::create([
            'asset_id' => $asset->id,
            'custodian_id' => $request->custodian_id,
            'room_id' => $request->room_id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return redirect()->route('assets.index')->with('success', 'Equipo registrado con éxito.');
    }
    public function edit(Asset $asset)
{
    // Es vital cargar estas dos listas para que los selectores del formulario funcionen
    $rooms = Room::all();
    $custodians = Custodian::all(); 

    // Enviamos TODO a la vista
    return view('admin.assets.edit', compact('asset', 'rooms', 'custodians'));
}

public function update(Request $request, Asset $asset)
{
    
    $validated = $request->validate([
        'room_id'       => 'required|exists:rooms,id',
        'serial_number' => 'required|unique:assets,serial_number,' . $asset->id,
        'internal_code' => 'nullable|unique:assets,internal_code,' . $asset->id,
        'custodian_id' => 'nullable|exists:custodians,id', // <--- REVISA ESTO
        'hostname'      => 'nullable|string',
        'ip_address'    => 'nullable|ip',
        'cpu'           => 'nullable|string',
        'ram'           => 'nullable|string',
        'storage'       => 'nullable|string',
        'monitor_asset'   => 'nullable|string',
        'monitor_serial'  => 'nullable|string',
        'keyboard_serial' => 'nullable|string',
        'mouse_serial'    => 'nullable|string',
        'security_guaya'  => 'nullable|string',
    ]);

    // Esto guarda todos los campos validados en la DB
    $asset->update($validated);

    return redirect()->route('assets.index')->with('success', 'Hoja de Vida actualizada correctamente.');
}
/**
 * Muestra la Hoja de Vida detallada de un equipo específico.
 */
public function show(Asset $asset)
{
    // Usamos "load" para traer las relaciones y evitar múltiples consultas a la BD (Eager Loading)
    $asset->load([
        'room.building.campus',      // Ubicación completa (Sede > Bloque > Salón)
        'history' => function($query) {
            $query->orderBy('created_at', 'desc'); // Historial del más reciente al más antiguo
        },
        'history.custodian',         // Quiénes fueron los responsables en el tiempo
        'history.room'               // Por qué oficinas pasó el equipo
    ]);

    // Buscamos quién es el responsable activo actualmente
    // Esto asume que tienes una relación 'currentAssignment' o similar en tu modelo Asset
    $currentAssignment = $asset->history()->where('status', 'active')->first();

    return view('admin.assets.show', compact('asset', 'currentAssignment'));
}

public function previewPdf(Asset $asset)
{
    // Cambiamos 'maintenances' por 'technicalServices'
    $asset->load(['currentCustodian', 'room.building', 'assignments', 'technicalServices']);
    
    
    return view('admin.assets.pdf_preview', compact('asset'));
    
    
}
}