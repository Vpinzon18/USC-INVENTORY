<?php

namespace App\Http\Controllers;

use App\Models\TechnicalService;
use App\Models\Asset;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use Spatie\LaravelPdf\Facades\Pdf;

class TechnicalServiceController extends Controller
{
   public function index(Request $request)
{
        $filters = $request->query();
    // 1. Consulta base con relaciones
    $query = \App\Models\TechnicalService::with(['asset.room.building', 'technician']);

    // --- APLICACIÓN DE FILTROS ---
    
    // Búsqueda por texto (Serial, Placa o Descripción)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('description', 'ilike', "%{$search}%") // usa 'like' si estás en MySQL
              ->orWhereHas('asset', function($qAsset) use ($search) {
                  $qAsset->where('serial_number', 'ilike', "%{$search}%")
                         ->orWhere('internal_code', 'ilike', "%{$search}%");
              });
        });
    }

    // Filtro por Técnico
    // Filtro por Técnico
    if ($request->filled('technician_id')) {
        
        $query->where('user_id', $request->technician_id); 
    }

    // Filtro por Tipo de Intervención
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Filtro por Edificio / Bloque
    if ($request->filled('building_id')) {
        $query->whereHas('asset.room', function($q) use ($request) {
            $q->where('building_id', $request->building_id);
        });
    }

    // Filtro por Dependencia
    if ($request->filled('dependency_id')) {
        $query->whereHas('asset.room', function($q) use ($request) {
            $q->where('dependency_id', $request->dependency_id);
        });
    }

    // Filtro por Fechas
    if ($request->filled('date_start')) {
    $query->where(
        'performed_at',
        '>=',
        $request->date_start
    );
}

if ($request->filled('date_end')) {
    $query->where(
        'performed_at',
        '<',
        \Carbon\Carbon::parse($request->date_end)
            ->addDay()
            ->startOfDay()
    );
}

    // --- CÁLCULO DE KPIs REALES (Clonamos para no dañar la consulta original) ---
    $kpis = (clone $query)
    ->selectRaw("
        COUNT(*) AS total,
        COUNT(*) FILTER (
            WHERE type = ?
        ) AS preventivos,
        COUNT(*) FILTER (
            WHERE type = ?
        ) AS correctivos,
        COUNT(*) FILTER (
            WHERE performed_at >= ?
              AND performed_at < ?
        ) AS este_mes
    ", [
        'PREVENTIVO',
        'CORRECTIVO',
        now()->startOfMonth(),
        now()->addMonth()->startOfMonth(),
    ])
    ->first()
    ->toArray();

    // --- PAGINACIÓN ---
    $perPage = \App\Support\PaginationOptions::fromRequest($request);
    $services = $query->orderBy('performed_at', 'desc')
                      ->paginate($perPage)
                      ->withQueryString();

    return view('admin.maintenances.index', compact('services', 'perPage', 'kpis', 'filters'));
}

   public function create(Request $request)
{
    // 1. Iniciamos una colección vacía por defecto (Carga ultra rápida)
    $assets = collect();

    // 2. ¿Viene preseleccionado un equipo desde otra vista? (Ej: ?asset_id=25)
    if ($request->filled('asset_id')) {
        $assets = Asset::with('room.building')
            ->where('id', $request->asset_id)
            ->get(); // Solo traemos 1 registro, no miles.
    }

    // 3. Consultamos el catálogo dinámico (Tipos de Intervención)
    $categories = \App\Models\Category::orderBy('name', 'asc')->get();

    return view('admin.maintenances.create', compact('assets', 'categories'));
}

    public function edit(int $id)
    {
        $maintenance = TechnicalService::findOrFail($id);
        
        // (Nota SOMA: Cuidado con usar all() si a futuro tienes miles de equipos. 
        // ¡Sería mejor usar AJAX aquí también como hicimos en la vista index!) 
        $assets = \App\Models\Asset::all(); 

        // 🚀 NUEVO: Consultamos el catálogo dinámico (Tipos de Intervención)
        $categories = \App\Models\Category::orderBy('name', 'asc')->get();

        return view('admin.maintenances.edit', compact('maintenance', 'assets', 'categories'));
    }

 public function store(Request $request)
{
    // 1. LA ADUANA (Validación)
    $validated = $request->validate([
        'asset_id'              => 'required|exists:assets,id',
        'performed_at'          => 'required|date',
        'description'           => 'required|string|min:3',
        'type'                  => 'required|string',
        'custom_type'           => 'nullable|string|max:150',
        'security_guaya'        => 'nullable|string|max:100',
        'maintenance_schedule_id' => 'nullable|exists:maintenance_schedules,id', 
    ]);

    // 2. ESCUDO LIGERO (Procesamiento de datos)
    $tipoBase = strip_tags($validated['type']);
    $tipoPersonalizado = isset($validated['custom_type']) ? strip_tags($validated['custom_type']) : null;
    $finalType = ($tipoBase === 'Otro') ? $tipoPersonalizado : $tipoBase;
    $cleanGuaya = isset($validated['security_guaya']) ? strip_tags($validated['security_guaya']) : null;

    // 3. ARTILLERÍA PESADA (Purifier)
    $cleanDescription = Purifier::clean($validated['description']);

    // 4. GUARDADO SEGURO
    // Usamos ?? null para evitar que el arreglo falle si el campo no fue enviado
    $service = \App\Models\TechnicalService::create([
        'asset_id'                => $validated['asset_id'],
        'user_id'                 => Auth::user()->id,
        'performed_at'            => $validated['performed_at'],
        'type'                    => $finalType,
        'description'             => $cleanDescription, 
        'security_guaya'          => $cleanGuaya,
        // CORRECCIÓN AQUÍ: Operador de fusión de null
        'maintenance_schedule_id' => $validated['maintenance_schedule_id'] ?? null,
    ]);

    // 5. LÓGICA DE ACTUALIZACIÓN DE CRONOGRAMA
    // Solo actualizamos si el tipo es preventivo y el ID existe realmente
    if (strtoupper($finalType) === 'PREVENTIVO' && !empty($validated['maintenance_schedule_id'] ?? null)) {
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
        $validated['type'] = strip_tags($validated['type']); 
        $validated['description'] = Purifier::clean($validated['description']); 

        // 3. ACTUALIZACIÓN SEGURA
        $maintenance->update($validated);

        return redirect()->route('maintenances.index')
            ->with('success', 'Bitácora actualizada de forma segura.');
    }

    // ==========================================
    // NUEVA FUNCIÓN: EXPORTAR FORMATO R-GT-051
    // ==========================================
    public function export(int $id)
    {
        // 1. Obtenemos el servicio con todas sus relaciones
        $service = TechnicalService::with([
            'asset.room.building', 
            'asset.category', 
            'technician'
        ])->findOrFail($id);

        $fileName = 'R-GT-051_BITACORA_' . $service->asset->serial_number . '_' . $service->id . '.pdf';

        // 2. Generamos y retornamos el PDF con Spatie
        // Al retornar directamente la fachada Pdf de Spatie, Laravel lo muestra automáticamente en el navegador
        return Pdf::view('admin.maintenances.pdf', compact('service'))
            ->format('Letter') // Formato carta. Puedes usar 'a4' si lo prefieres
            ->margins(10, 10, 10, 10) // Márgenes en milímetros (opcional, ajústalo a tu formato)
            ->name($fileName);
    }
    public function destroy(TechnicalService $maintenance)
    {
        $scheduleId = $maintenance->maintenance_schedule_id;

        DB::transaction(function () use ($maintenance, $scheduleId): void {
            $maintenance->delete();

            if ($scheduleId && !TechnicalService::where('maintenance_schedule_id', $scheduleId)->exists()) {
                MaintenanceSchedule::whereKey($scheduleId)
                    ->where('status', 'REALIZADO')
                    ->update(['status' => 'PENDIENTE']);
            }
        });

        return redirect()->route('maintenances.index')
            ->with('success', 'Bitácora eliminada correctamente.');
    }
}