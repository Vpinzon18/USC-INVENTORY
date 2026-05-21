<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\LaravelPdf\Facades\Pdf;

class MaintenanceScheduleController extends Controller
{
  public function index(Request $request)
{
    // 1. Capturamos la cantidad dinámica de filas (por defecto 10)
    $perPage = $request->input('per_page', 10);

    // Candado de seguridad para el selector del footer
    if (!in_array($perPage, [5, 10, 15, 25, 50])) {
        $perPage = 10;
    }

    // 2. Iniciamos la consulta base cargando el árbol de relaciones con 'campus'
    $query = MaintenanceSchedule::with(['asset.room.building.campus', 'technician']);

    // 3. PROCESAMIENTO DE REGLAS DINÁMICAS
    if ($request->filled('rules') && is_array($request->rules)) {
        foreach ($request->rules as $rule) {
            $field = $rule['field'] ?? null;
            $operator = $rule['operator'] ?? 'contains';
            $value = $rule['value'] ?? null;

            if (!$field || is_null($value) || $value === '') {
                continue;
            }

            $query->where(function($q) use ($field, $operator, $value) {
                
                // REGLA: Filtrar por características del Equipo / Aula
                if ($field === 'asset') {
                    $q->whereHas('asset', function($assetQ) use ($operator, $value) {
                        if ($operator === 'equals') {
                            $assetQ->where('serial_number', $value)->orWhere('internal_code', $value);
                        } else {
                            $assetQ->where('serial_number', 'ilike', "%{$value}%")->orWhere('internal_code', 'ilike', "%{$value}%");
                        }
                    })
                    ->orWhereHas('asset.room', function($roomQ) use ($operator, $value) {
                        if ($operator === 'equals') {
                            $roomQ->where('nomenclatura', $value);
                        } else {
                            $roomQ->where('nomenclatura', 'ilike', "%{$value}%");
                        }
                    });
                } 
                
                // REGLA CORREGIDA: Filtrar por Campus (Usa relación 'campus' y campus_id)
                elseif ($field === 'sede') {
                    $q->whereHas('asset.room.building', function($buildingQ) use ($value) {
                        $buildingQ->where('campus_id', $value); // Sincronizado con el modelo Campus
                    });
                }

                // REGLA: Filtrar por Bloque / Edificio
                elseif ($field === 'building') {
                    $q->whereHas('asset.room', function($roomQ) use ($value) {
                        $roomQ->where('building_id', $value);
                    });
                }
                
                // REGLA: Filtrar por Técnico Asignado
                elseif ($field === 'technician') {
                    if ($operator === 'equals') {
                        $q->where('technician_id', $value);
                    } else {
                        $q->whereHas('technician', function($techQ) use ($value) {
                            $techQ->where('name', 'ilike', "%{$value}%");
                        });
                    }
                } 
                
                // REGLA: Filtrar por el Estado de la Agenda
                elseif ($field === 'status') {
                    $q->where('status', $value);
                }
            });
        }
    }

    // 4. Ordenamiento operativo y paginación
    $schedules = $query->orderByRaw("CASE 
            WHEN status = 'PENDIENTE' THEN 1 
            WHEN status = 'VENCIDO' THEN 2 
            ELSE 3 
        END")
        ->orderBy('scheduled_date', 'asc')
        ->paginate($perPage)
        ->withQueryString();

    // 5. CARGA DE CATÁLOGOS CORREGIDA (Llamamos a \App\Models\Campus)
    $technicians = \App\Models\User::orderBy('name', 'asc')->get();
    $sedes = \App\Models\Campus::orderBy('name', 'asc')->get(); // Llamada al modelo real
    $buildings = \App\Models\Building::orderBy('name', 'asc')->get();

    // 6. Mantenemos el estado de las reglas activas
    $currentRules = $request->input('rules', [['field' => 'asset', 'operator' => 'contains', 'value' => '']]);

    return view('admin.schedules.index', compact('schedules', 'technicians', 'sedes', 'buildings', 'perPage', 'currentRules'));
}

// 1. Limpiamos la vista de creación para que cargue instantáneamente
public function create()
{
    // Solo cargamos los técnicos (que son pocos), YA NO cargamos los Activos aquí.
    $technicians = \App\Models\User::orderBy('name', 'asc')->get(); 

    return view('admin.schedules.create', compact('technicians'));
}

// 2. NUEVO MÉTODO: El motor de búsqueda silencioso (API Local)
public function searchAssets(Request $request)
{
    $search = $request->input('q');

    // Si no escriben nada o menos de 2 letras, no buscamos nada para ahorrar memoria
    if (empty($search) || strlen($search) < 2) {
        return response()->json([]);
    }

    // Buscamos solo las 10 mejores coincidencias y cruzamos con su ubicación
    $assets = \App\Models\Asset::with('room.building')
        ->where('serial_number', 'ilike', "%{$search}%")
        ->orWhere('internal_code', 'ilike', "%{$search}%")
        ->limit(10) 
        ->get();

    return response()->json($assets);
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
// 3. Guarda las programaciones masivas en la base de datos
public function store(Request $request)
{
    // 1. Validamos que venga la lista de equipos, el técnico y la fecha
    $request->validate([
        'asset_ids'      => 'required|array|min:1', // Debe ser un arreglo con al menos 1 equipo
        'asset_ids.*'    => 'exists:assets,id',     // Cada equipo debe existir en la base de datos
        'technician_id'  => 'required|exists:users,id',
        'scheduled_date' => 'required|date|after_or_equal:today',
    ]);

    // 2. Recorremos el arreglo y creamos una programación individual para cada equipo
    foreach ($request->asset_ids as $asset_id) {
        MaintenanceSchedule::create([
            'asset_id'       => $asset_id,
            'technician_id'  => $request->technician_id,
            'scheduled_date' => $request->scheduled_date,
            'status'         => 'PENDIENTE', // Estado inicial por defecto
        ]);
    }

    // 3. Retornamos al cronograma con un mensaje de confirmación
    return redirect()->route('schedules.index')
        ->with('success', 'Se han programado ' . count($request->asset_ids) . ' equipos con éxito para mantenimiento.');
}

// 4. Exportación a PDF (Formato Oficial R-GT-051) con Spatie PDF
public function export(Request $request)
{
    // 1. APLICAMOS EL EAGER LOADING DE LA RELACIÓN 'technicalService'
    $query = MaintenanceSchedule::with([
        'asset.room.building.campus', 
        'technician', 
        'technicalService' // <--- RELACIÓN FORMAL PRECARGADA
    ]);

    // 2. Aplicamos las reglas de filtrado dinámicas
    if ($request->filled('rules') && is_array($request->rules)) {
        foreach ($request->rules as $rule) {
            $field = $rule['field'] ?? null;
            $operator = $rule['operator'] ?? 'contains';
            $value = $rule['value'] ?? null;

            if (!$field || is_null($value) || $value === '') continue;

            $query->where(function($q) use ($field, $operator, $value) {
                if ($field === 'asset') {
                    $q->whereHas('asset', function($assetQ) use ($operator, $value) {
                        if ($operator === 'equals') {
                            $assetQ->where('serial_number', $value)->orWhere('internal_code', $value);
                        } else {
                            $assetQ->where('serial_number', 'ilike', "%{$value}%")->orWhere('internal_code', 'ilike', "%{$value}%");
                        }
                    })->orWhereHas('asset.room', function($roomQ) use ($operator, $value) {
                        if ($operator === 'equals') {
                            $roomQ->where('nomenclatura', $value);
                        } else {
                            $roomQ->where('nomenclatura', 'ilike', "%{$value}%");
                        }
                    });
                } 
                elseif ($field === 'sede') {
                    $q->whereHas('asset.room.building', function($buildingQ) use ($value) {
                        $buildingQ->where('campus_id', $value);
                    });
                }
                elseif ($field === 'building') {
                    $q->whereHas('asset.room', function($roomQ) use ($value) {
                        $roomQ->where('building_id', $value);
                    });
                }
                elseif ($field === 'technician') {
                    if ($operator === 'equals') {
                        $q->where('technician_id', $value);
                    } else {
                        $q->whereHas('technician', function($techQ) use ($value) {
                            $techQ->where('name', 'ilike', "%{$value}%");
                        });
                    }
                } 
                elseif ($field === 'status') {
                    $q->where('status', $value);
                }
            });
        }
    }

    $schedules = $query->orderBy('scheduled_date', 'asc')->get();

    // 3. MATEMÁTICA DE CALIDAD ISO (Múltiplos exactos de 15 filas)
    $totalRecords = $schedules->count();
    $paddingNeeded = ($totalRecords == 0 || $totalRecords % 15 === 0) ? 0 : (15 - ($totalRecords % 15));
    if ($totalRecords == 0) $paddingNeeded = 15;

    // 4. CHUNKING: Creamos un arreglo unificado
    $allRows = collect();
    
    foreach ($schedules as $schedule) {
        $allRows->push((object)[
            'is_empty' => false,
            'data' => $schedule
        ]);
    }
    
    for ($i = 0; $i < $paddingNeeded; $i++) {
        $allRows->push((object)['is_empty' => true]);
    }

    $pages = $allRows->chunk(15);

    $fileName = 'Formato_R-GT-051_' . date('Y_m_d_H_i') . '.pdf';

    // 5. Renderizado con Spatie PDF
    return Pdf::view('admin.schedules.pdf-r-gt-051', compact('pages'))
        ->format('Letter')     
        ->landscape()          
        ->margins(10, 10, 10, 10) 
        ->download($fileName); 
}
}