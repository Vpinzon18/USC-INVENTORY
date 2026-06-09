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
        $perPage = $request->input('per_page', 10);

        if (!in_array($perPage, [5, 10, 15, 25, 50])) {
            $perPage = 10;
        }

        $query = MaintenanceSchedule::with(['asset.room.building.campus', 'technician']);

        if ($request->filled('rules') && is_array($request->rules)) {
            foreach ($request->rules as $rule) {
                $field = $rule['field'] ?? null;
                $operator = $rule['operator'] ?? 'contains';
                $value = $rule['value'] ?? null;

                if (!$field || is_null($value) || $value === '') {
                    continue;
                }

                $query->where(function ($q) use ($field, $operator, $value) {

                    // REGLA: Filtrar por características del Equipo / Aula
                    if ($field === 'asset') {
                        $q->whereHas('asset', function ($assetQ) use ($operator, $value) {
                            if ($operator === 'equals') {
                                $assetQ->where('serial_number', $value)->orWhere('internal_code', $value);
                            } else {
                                $assetQ->where('serial_number', 'ilike', "%{$value}%")->orWhere('internal_code', 'ilike', "%{$value}%");
                            }
                        })
                            ->orWhereHas('asset.room', function ($roomQ) use ($operator, $value) {
                                if ($operator === 'equals') {
                                    $roomQ->where('nomenclatura', $value);
                                } else {
                                    $roomQ->where('nomenclatura', 'ilike', "%{$value}%");
                                }
                            });
                    }

                    // REGLA CORREGIDA: Filtrar por Campus (Usa relación 'campus' y campus_id)
                    elseif ($field === 'sede') {
                        $q->whereHas('asset.room.building', function ($buildingQ) use ($value) {
                            $buildingQ->where('campus_id', $value); // Sincronizado con el modelo Campus
                        });
                    }

                    // REGLA: Filtrar por Bloque / Edificio
                    elseif ($field === 'building') {
                        $q->whereHas('asset.room', function ($roomQ) use ($value) {
                            $roomQ->where('building_id', $value);
                        });
                    }

                    // REGLA: Filtrar por Técnico Asignado
                    elseif ($field === 'technician') {
                        if ($operator === 'equals') {
                            $q->where('technician_id', $value);
                        } else {
                            $q->whereHas('technician', function ($techQ) use ($value) {
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

        $technicians = \App\Models\User::orderBy('name', 'asc')->get();
        $sedes = \App\Models\Campus::orderBy('name', 'asc')->get();
        $buildings = \App\Models\Building::orderBy('name', 'asc')->get();

        $currentRules = $request->input('rules', [['field' => 'asset', 'operator' => 'contains', 'value' => '']]);

        return view('admin.schedules.index', compact('schedules', 'technicians', 'sedes', 'buildings', 'perPage', 'currentRules'));
    }

    public function create()
    {
        $technicians = \App\Models\User::orderBy('name', 'asc')->get();

        return view('admin.schedules.create', compact('technicians'));
    }

    public function searchAssets(Request $request)
    {
        $search = $request->input('q');
        if (empty($search) || strlen($search) < 2) {
            return response()->json([]);
        }

        $assets = \App\Models\Asset::with('room.building')
            ->where('serial_number', 'ilike', "%{$search}%")
            ->orWhere('internal_code', 'ilike', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json($assets);
    }
    public function edit(MaintenanceSchedule $schedule)
{
    // Cargamos todos los activos y usuarios para permitir la reasignación
    $assets = Asset::with('room')->orderBy('serial_number', 'asc')->get();
    $technicians = User::orderBy('name', 'asc')->get();

    return view('admin.schedules.edit', compact('schedule', 'assets', 'technicians'));
}

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
    public function store(Request $request)
    {
        $request->validate([
            'asset_ids'      => 'required|array|min:1',
            'asset_ids.*'    => 'exists:assets,id',
            'technician_id'  => 'required|exists:users,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
        ]);

        foreach ($request->asset_ids as $asset_id) {
            MaintenanceSchedule::create([
                'asset_id'       => $asset_id,
                'technician_id'  => $request->technician_id,
                'scheduled_date' => $request->scheduled_date,
                'status'         => 'PENDIENTE',
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Se han programado ' . count($request->asset_ids) . ' equipos con éxito para mantenimiento.');
    }
    public function export(Request $request)
    {
        $query = MaintenanceSchedule::with([
            'asset.room.building.campus',
            'technician',
            'technicalService'
        ]);


        if ($request->filled('rules') && is_array($request->rules)) {
            foreach ($request->rules as $rule) {
                $field = $rule['field'] ?? null;
                $operator = $rule['operator'] ?? 'contains';
                $value = $rule['value'] ?? null;

                if (!$field || is_null($value) || $value === '') continue;

                $query->where(function ($q) use ($field, $operator, $value) {
                    if ($field === 'asset') {
                        $q->whereHas('asset', function ($assetQ) use ($operator, $value) {
                            if ($operator === 'equals') {
                                $assetQ->where('serial_number', $value)->orWhere('internal_code', $value);
                            } else {
                                $assetQ->where('serial_number', 'ilike', "%{$value}%")->orWhere('internal_code', 'ilike', "%{$value}%");
                            }
                        })->orWhereHas('asset.room', function ($roomQ) use ($operator, $value) {
                            if ($operator === 'equals') {
                                $roomQ->where('nomenclatura', $value);
                            } else {
                                $roomQ->where('nomenclatura', 'ilike', "%{$value}%");
                            }
                        });
                    } elseif ($field === 'sede') {
                        $q->whereHas('asset.room.building', function ($buildingQ) use ($value) {
                            $buildingQ->where('campus_id', $value);
                        });
                    } elseif ($field === 'building') {
                        $q->whereHas('asset.room', function ($roomQ) use ($value) {
                            $roomQ->where('building_id', $value);
                        });
                    } elseif ($field === 'technician') {
                        if ($operator === 'equals') {
                            $q->where('technician_id', $value);
                        } else {
                            $q->whereHas('technician', function ($techQ) use ($value) {
                                $techQ->where('name', 'ilike', "%{$value}%");
                            });
                        }
                    } elseif ($field === 'status') {
                        $q->where('status', $value);
                    }
                });
            }
        }

        $schedules = $query->orderBy('scheduled_date', 'asc')->get();
        $totalRecords = $schedules->count();
        $paddingNeeded = ($totalRecords == 0 || $totalRecords % 15 === 0) ? 0 : (15 - ($totalRecords % 15));
        if ($totalRecords == 0) $paddingNeeded = 15;
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

        return Pdf::view('admin.schedules.pdf-r-gt-051', compact('pages'))
            ->format('Letter')
            ->landscape()
            ->margins(10, 10, 10, 10)
            ->download($fileName);
    }
}
