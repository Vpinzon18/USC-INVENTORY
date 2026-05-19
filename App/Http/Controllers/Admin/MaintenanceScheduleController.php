<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\User;

class MaintenanceScheduleController extends Controller
{
   public function index(Request $request)
{
    $perPage = $request->input('per_page', 10);

    // Cargamos las relaciones y aplicamos el ordenamiento histórico dinámico
    $schedules = MaintenanceSchedule::with(['asset.room.building', 'technician'])
        ->orderByRaw("CASE 
            WHEN status = 'PENDIENTE' THEN 1 
            WHEN status = 'VENCIDO' THEN 2 
            ELSE 3 
        END")
        ->orderBy('scheduled_date', 'asc')
        ->paginate($perPage)
        ->withQueryString(); // Crucial para que no se pierda el 'per_page' al cambiar de página

    return view('admin.schedules.index', compact('schedules', 'perPage'));
}

public function create()
{
    $assets = Asset::with('room')->orderBy('serial_number', 'asc')->get();
    
    // Traemos todos los usuarios del sistema para listarlos como técnicos
    $technicians = \App\Models\User::orderBy('name', 'asc')->get(); 

    return view('admin.schedules.create', compact('assets', 'technicians'));
}

public function store(Request $request)
{
    // Validamos que asset_ids sea un arreglo obligatorio y contenga elementos válidos
    $request->validate([
        'asset_ids'      => 'required|array|min:1',
        'asset_ids.*'    => 'required|exists:assets,id',
        'technician_id'  => 'required|exists:users,id',
        'scheduled_date' => 'required|date|after_or_equal:today',
    ]);

    // Recorremos cada ID de equipo seleccionado en la vista
    foreach ($request->asset_ids as $assetId) {
        MaintenanceSchedule::create([
            'asset_id'       => $assetId,
            'technician_id'  => $request->technician_id,
            'scheduled_date' => $request->scheduled_date,
            'status'         => 'PENDIENTE',
        ]);
    }

    // Contamos cuántos equipos se procesaron para dar un mensaje claro
    $totalEquipos = count($request->asset_ids);

    return redirect()->route('schedules.index')
        ->with('success', "Se han programado exitosamente {$totalEquipos} equipos en el cronograma semestral.");
}

public function edit(MaintenanceSchedule $schedule)
{
    // Cargamos todos los activos y usuarios para permitir la reasignación
    $assets = Asset::with('room')->orderBy('serial_number', 'asc')->get();
    $technicians = User::orderBy('name', 'asc')->get();

    return view('admin.schedules.edit', compact('schedule', 'assets', 'technicians'));
}

// 2. Procesa la actualización en PostgreSQL
public function update(Request $request, MaintenanceSchedule $schedule)
{
    $request->validate([
        'asset_id'       => 'required|exists:assets,id',
        'technician_id'  => 'required|exists:users,id',
        'scheduled_date' => 'required|date|after_or_equal:today',
    ]);

    $schedule->update([
        'asset_id'       => $request->asset_id,
        'technician_id'  => $request->technician_id,
        'scheduled_date' => $request->scheduled_date,
    ]);

    return redirect()->route('schedules.index')
        ->with('success', 'Programación de mantenimiento actualizada con éxito.');
}
}