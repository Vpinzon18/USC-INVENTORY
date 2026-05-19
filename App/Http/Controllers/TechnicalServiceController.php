<?php

namespace App\Http\Controllers;

use App\Models\TechnicalService;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicalServiceController extends Controller
{
    /**
     * Muestra el historial global de mantenimientos.
     */
public function index(Request $request)
{
    // 1. Capturamos los parámetros de la petición
    $search = $request->input('search');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    
    // Capturamos el límite dinámico (por defecto 10 para la bitácora)
    $perPage = $request->input('per_page', 10);

    $query = TechnicalService::with(['asset.room.building', 'technician']);

    // 2. Aplicamos el Buscador Unificado
    if ($request->filled('search')) {
        $query->where(function($q) use ($search) {
            // Busca en la tabla de Activos (Serial o Placa)
            $q->whereHas('asset', function($assetQuery) use ($search) {
                $assetQuery->where('serial_number', 'ilike', "%{$search}%")
                           ->orWhere('internal_code', 'ilike', "%{$search}%");
            })
            // Busca por el nombre del Técnico
            ->orWhereHas('technician', function($techQuery) use ($search) {
                $techQuery->where('name', 'ilike', "%{$search}%");
            })
            // Busca en la descripción
            ->orWhere('description', 'ilike', "%{$search}%");
        });
    }

    // 3. Aplicamos Filtros de fecha
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('performed_at', [$fromDate, $toDate]);
    }

    // 4. Ejecutamos la paginación con el límite dinámico
    $services = $query->latest('performed_at')
                      ->paginate($perPage)
                      ->withQueryString(); // Mantiene search, from_date, to_date y per_page en los links

    // 5. Retornamos la vista con todos los datos necesarios para mantener los inputs llenos
    return view('admin.maintenances.index', compact('services', 'search', 'fromDate', 'toDate', 'perPage'));
}

    /**
     * Vista para que el técnico cree un nuevo registro.
     */
    public function create(Request $request)
{
    // 1. Iniciamos la consulta base cargando las relaciones necesarias
    $query = Asset::with('room.building');

    // 2. ¡EL INTERRUPTOR! Si la URL contiene un asset_id (viene desde el cronograma)
    // filtramos la base de datos para traer ÚNICAMENTE ese equipo
    if ($request->filled('asset_id')) {
        $query->where('id', $request->asset_id);
    }

    // 3. Ejecutamos la consulta manteniendo tu orden por número de serial
    $assets = $query->orderBy('serial_number')->get();
    
    return view('admin.maintenances.create', compact('assets'));
}

    /**
     * Almacena la intervención técnica en la base de datos.
     */
    public function store(Request $request)
{
    $finalType = ($request->type_selector === 'Otro') 
                 ? $request->custom_type 
                 : $request->type_selector;

    $request->validate([
        'asset_id'       => 'required|exists:assets,id',
        'performed_at'   => 'required|date',
        'description'    => 'required|string|min:3', 
        'security_guaya' => 'nullable|string|max:255',
    ]);

    // 1. Guardamos el registro histórico unificado en la bitácora
    \App\Models\TechnicalService::create([
        'asset_id'     => $request->asset_id,
        'user_id'      => Auth::user()->id,
        'performed_at' => $request->performed_at,
        'type'         => $finalType,
        'description'  => $request->description,
    ]);

    // 2. Si se reportó un reemplazo de guaya, actualizamos el activo
    if ($request->filled('security_guaya')) {
        $asset = \App\Models\Asset::findOrFail($request->asset_id);
        $asset->update([
            'security_guaya' => $request->security_guaya
        ]);
    }

    // 3. ¡CONEXIÓN AUTOMÁTICA CON EL CRONOGRAMA!
    // Si la intervención fue un Mantenimiento Preventivo, cerramos la tarea pendiente
    if (strtoupper($finalType) === 'PREVENTIVO') {
        \App\Models\MaintenanceSchedule::where('asset_id', $request->asset_id)
            ->where('status', 'PENDIENTE')
            ->orderBy('scheduled_date', 'asc') // Tomamos el más antiguo programado
            ->first()
            ?->update(['status' => 'REALIZADO']); // Cerramos el compromiso semestral
    }

    return redirect()->route('maintenances.index')
        ->with('success', 'Mantenimiento preventivo registrado y cronograma actualizado con éxito.');
}

public function edit(int $id)
{
    // El nombre de esta variable debe ser 'maintenance' para que coincida con tu vista
    $maintenance = TechnicalService::findOrFail($id); 
    $assets = \App\Models\Asset::all(); 

    // Aquí enviamos 'maintenance' (sin el $)
    return view('admin.maintenances.edit', compact('maintenance', 'assets'));
}
public function update(Request $request, TechnicalService $maintenance) 
{
    $validated = $request->validate([
        'performed_at' => 'required|date',
        'type'         => 'required|string',
        'description'  => 'required|string|min:5',
        'asset_id'     => 'required|exists:assets,id',
    ]);

    // Usamos directamente la instancia inyectada
    $maintenance->update($validated);

    return redirect()->route('maintenances.index')
        ->with('success', 'Bitácora actualizada correctamente.');
}
}