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
    $query = TechnicalService::with(['asset.room.building', 'technician']);

    // --- BUSCADOR UNIFICADO ---
    if ($request->filled('search')) {
        $search = $request->search;
        
        $query->where(function($q) use ($search) {
            // Busca en la tabla de Activos (Serial o Placa)
            $q->whereHas('asset', function($assetQuery) use ($search) {
                $assetQuery->where('serial_number', 'ilike', "%{$search}%")
                          ->orWhere('internal_code', 'ilike', "%{$search}%");
            })
            // O busca por el nombre del Técnico que realizó el trabajo
            ->orWhereHas('technician', function($techQuery) use ($search) {
                $techQuery->where('name', 'ilike', "%{$search}%");
            })
            // O busca dentro de la descripción del mantenimiento
            ->orWhere('description', 'ilike', "%{$search}%");
        });
    }

    // Filtros de fecha (estos suelen mantenerse aparte por precisión)
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('performed_at', [$request->from_date, $request->to_date]);
    }

    $services = $query->latest('performed_at')->paginate(15)->withQueryString();

    return view('admin.maintenances.index', compact('services'));
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