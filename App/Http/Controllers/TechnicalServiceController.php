<?php

namespace App\Http\Controllers;

use App\Models\TechnicalService;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

class TechnicalServiceController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 15, 25, 50])) {
            $perPage = 10;
        }

        $query = TechnicalService::with(['asset.room.building', 'technician']);


        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {

                $q->whereHas('asset', function ($assetQuery) use ($search) {
                    $assetQuery->where('serial_number', 'ilike', "%{$search}%")
                        ->orWhere('internal_code', 'ilike', "%{$search}%");
                })

                    ->orWhereHas('technician', function ($techQuery) use ($search) {
                        $techQuery->where('name', 'ilike', "%{$search}%");
                    })

                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }


        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('performed_at', [$fromDate, $toDate]);
        }


        $services = $query->latest('performed_at')
            ->paginate($perPage)
            ->withQueryString();
        return view('admin.maintenances.index', compact('services', 'search', 'fromDate', 'toDate', 'perPage'));
    }

    public function create(Request $request)
    {
        $query = Asset::with('room.building');

        if ($request->filled('asset_id')) {
            $query->where('id', $request->asset_id);
        }

        $assets = $query->orderBy('serial_number')->get();

        return view('admin.maintenances.create', compact('assets'));
    }

        public function edit(int $id)
    {

        $maintenance = TechnicalService::findOrFail($id);
        $assets = \App\Models\Asset::all();
        return view('admin.maintenances.edit', compact('maintenance', 'assets'));
    }

   public function store(Request $request)
    {
        // 1. LA ADUANA: Validamos TODOS los campos que van a entrar a la BD
        $validated = $request->validate([
            'asset_id'                => 'required|exists:assets,id',
            'performed_at'            => 'required|date',
            'description'             => 'required|string|min:3',
            'type'                    => 'required|string',
            'custom_type'             => 'nullable|string|max:150', // Agregado
            'security_guaya'          => 'nullable|string|max:100', // Agregado
            'maintenance_schedule_id' => 'nullable|exists:maintenance_schedules,id', // Agregado
        ]);

        // 2. ESCUDO LIGERO (strip_tags) para campos cortos
        $tipoBase = strip_tags($validated['type']);
        $tipoPersonalizado = isset($validated['custom_type']) ? strip_tags($validated['custom_type']) : null;
        
        $finalType = ($tipoBase === 'Otro') ? $tipoPersonalizado : $tipoBase;

        $cleanGuaya = isset($validated['security_guaya']) ? strip_tags($validated['security_guaya']) : null;

        // 3. ARTILLERÍA PESADA (Purifier) para el texto enriquecido
        $cleanDescription = Purifier::clean($validated['description']);

        // 4. GUARDADO SEGURO
        \App\Models\TechnicalService::create([
            'asset_id'                => $validated['asset_id'],
            'user_id'                 => Auth::user()->id,
            'performed_at'            => $validated['performed_at'],
            'type'                    => $finalType,
            'description'             => $cleanDescription, // Salvado por Purifier
            'security_guaya'          => $cleanGuaya,
            'maintenance_schedule_id' => $validated['maintenance_schedule_id'],
        ]);

        if (strtoupper($finalType) === 'PREVENTIVO' && !empty($validated['maintenance_schedule_id'])) {
            \App\Models\MaintenanceSchedule::where('id', $validated['maintenance_schedule_id'])
                ->update(['status' => 'REALIZADO']);
        }

        return redirect()->route('maintenances.index')
            ->with('success', 'Registro de servicio guardado de forma segura.');
    }


    public function update(Request $request, TechnicalService $maintenance)
    {
        // 1. LA ADUANA
        $validated = $request->validate([
            'performed_at' => 'required|date',
            'type'         => 'required|string|max:150',
            'description'  => 'required|string|min:5',
            'asset_id'     => 'required|exists:assets,id',
        ]);

        // 2. DEFENSA MIXTA
        $validated['type'] = strip_tags($validated['type']); // Escudo Ligero
        $validated['description'] = Purifier::clean($validated['description']); // Artillería Pesada

        // 3. ACTUALIZACIÓN SEGURA
        $maintenance->update($validated);

        return redirect()->route('maintenances.index')
            ->with('success', 'Bitácora actualizada de forma segura.');
    }}
