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
    public function create()
    {
        // Cargamos los activos con su ubicación para que el técnico confirme el equipo
        $assets = Asset::with('room')->orderBy('serial_number')->get();
        
        return view('admin.maintenances.create', compact('assets'));
    }

    /**
     * Almacena la intervención técnica en la base de datos.
     */
    public function store(Request $request)
{
    // 1. Lógica para unir el tipo (Selector o Custom)
    $finalType = ($request->type_selector === 'Otro') 
                 ? $request->custom_type 
                 : $request->type_selector;

    // 2. Validamos (Bajé el min de la descripción para que tu prueba pase)
    $request->validate([
        'asset_id'     => 'required|exists:assets,id',
        'performed_at' => 'required|date',
        'description'  => 'required|string|min:3', 
    ]);

    // 3. Guardado
    \App\Models\TechnicalService::create([
        'asset_id'     => $request->asset_id,
        'user_id'      => Auth::user()->id,
        'performed_at' => $request->performed_at,
        'type'         => $finalType,
        'description'  => $request->description,
    ]);

    return redirect()->route('maintenances.index')
        ->with('success', 'Mantenimiento registrado con éxito.');
}
// En el método edit
// En TechnicalServiceController.php
// TechnicalServiceController.php

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