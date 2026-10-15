<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Notifications\MaintenanceAssignedNotification;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Facades\Pdf;

class MaintenanceScheduleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query();
        $perPage = \App\Support\PaginationOptions::fromRequest($request);


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

                    // REGLA: Filtrar por Campus (Sede)
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

                    // 🚀 NUEVA REGLA: Filtrar por Salón / Espacio Físico (AJAX)
                    elseif ($field === 'room') {
                        $q->whereHas('asset', function ($assetQ) use ($value) {
                            $assetQ->where('room_id', $value);
                        });
                    }

                    // 🚀 NUEVA REGLA: Filtrar por Dependencia Organizacional (AJAX)
                    elseif ($field === 'dependency') {
                        $q->whereHas('asset.custodian', function ($custodianQ) use ($value) {
                            $custodianQ->where('dependency_id', $value);
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

        $technicians = [];
        $sedes = [];
        $buildings = [];

        // Aseguramos que la estructura inicial tenga la variable 'text' para el buscador AJAX
        $currentRules = $request->input('rules', [['field' => 'asset', 'operator' => 'contains', 'value' => '', 'text' => '']]);

        return view('admin.schedules.index', compact('schedules', 'technicians', 'sedes', 'buildings', 'perPage', 'currentRules', 'filters'));
    }

    public function create()
    {
        $technicians = \App\Models\User::where('role', 2)->orderBy('name', 'asc')->get();

        return view('admin.schedules.create', compact('technicians'));
    }

    public function calendar()
    {
        $schedules = MaintenanceSchedule::query()
            ->with([
                'asset.room.building.campus',
                'asset.currentCustodian',
                'asset.custodian',
                'technician',
                'technicalService.technician',
            ])
            ->orderBy('scheduled_date')
            ->get();

        $maintenanceRows = $schedules->map(function (MaintenanceSchedule $schedule): array {
            $asset = $schedule->asset;
            $room = $asset?->room;
            $building = $room?->building;
            $service = $schedule->technicalService;
            $custodian = $asset?->currentCustodian ?? $asset?->custodian;

            return [
                'id' => $schedule->id,
                'scheduledDate' => $schedule->scheduled_date,
                'status' => $schedule->status,
                'technicianId' => $schedule->technician_id,
                'technician' => $schedule->technician?->name ?? 'Técnico no asignado',
                'performedDate' => $service?->performed_at
                    ? \Illuminate\Support\Carbon::parse($service->performed_at)->toDateString()
                    : null,
                'performer' => $service?->technician?->name,
                'serviceType' => $service?->type,
                'assetName' => trim(implode(' ', array_filter([
                    $asset?->manufacturer,
                    $asset?->model_version,
                ]))) ?: ($asset?->hostname ?: 'Activo sin nombre'),
                'serial' => $asset?->serial_number ?? 'Sin serial',
                'plate' => $asset?->internal_code ?? 'Sin placa',
                'location' => implode(' · ', array_filter([
                    $building?->campus?->name,
                    $building?->name,
                    $room?->nomenclatura ?: $room?->name,
                ])) ?: 'Ubicación sin asignar',
                'campus' => $building?->campus?->name ?? 'Sin sede',
                'building' => $building?->name ?? 'Sin bloque',
                'room' => $room?->nomenclatura ?: ($room?->name ?? 'Sin espacio'),
                'custodian' => $custodian?->full_name ?? 'Sin custodiante',
                'executionUrl' => $schedule->status === 'REALIZADO' || $service
                    || !in_array((int) auth()->user()->role, [1, 2], true)
                    || ((int) auth()->user()->role === 2 && (int) $schedule->technician_id !== (int) auth()->id())
                    ? null
                    : route('maintenances.create', ['asset_id' => $asset?->id, 'schedule_id' => $schedule->id]),
            ];
        })->values();

        $today = now()->toDateString();
        $focusDate = $schedules->first(fn (MaintenanceSchedule $schedule) => $schedule->scheduled_date >= $today)?->scheduled_date
            ?? $schedules->last()?->scheduled_date
            ?? $today;
        $calendarMonth = \Illuminate\Support\Carbon::parse($focusDate)->format('Y-m');

        return view('admin.schedules.calendar', compact('maintenanceRows', 'calendarMonth'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_ids' => ['required', 'array', 'min:1'],
            'asset_ids.*' => ['required', 'integer', 'distinct', 'exists:assets,id'],
            'technician_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 2)],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['sometimes', 'in:PENDIENTE,REALIZADO,VENCIDO'],
        ]);

        $status = $validated['status'] ?? 'PENDIENTE';

        $schedules = DB::transaction(function () use ($validated, $status): array {
            $createdSchedules = [];
            foreach ($validated['asset_ids'] as $assetId) {
                $createdSchedules[] = MaintenanceSchedule::create([
                    'asset_id' => $assetId,
                    'technician_id' => $validated['technician_id'],
                    'scheduled_date' => $validated['scheduled_date'],
                    'status' => $status,
                ]);
            }
            return $createdSchedules;
        });

        $technician = User::findOrFail($validated['technician_id']);
        foreach ($schedules as $assignedSchedule) {
            $technician->notify(new MaintenanceAssignedNotification($assignedSchedule->load('asset')));
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Cronograma creado correctamente.');
    }
    public function searchAssets(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['q'] ?? '');
        if ($search === '' || mb_strlen($search) < 2) {
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
    $technicians = User::where('role', 2)->orderBy('name', 'asc')->get();

    return view('admin.schedules.edit', compact('schedule', 'assets', 'technicians'));
}

    public function update(Request $request, MaintenanceSchedule $schedule)
    {
        $validated = $request->validate([
            'asset_ids' => ['required', 'array', 'min:1'],
            'asset_ids.*' => ['required', 'integer', 'distinct', 'exists:assets,id'],
            'technician_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 2)],
            'scheduled_date' => ['required', 'date'],
            'status' => ['required', 'in:PENDIENTE,REALIZADO,VENCIDO'],
        ]);

        $assetIds = array_values($validated['asset_ids']);
        $assignmentChanged = (int) $schedule->technician_id !== (int) $validated['technician_id'] || (int) $schedule->asset_id !== (int) $assetIds[0];
        $additionalSchedules = DB::transaction(function () use ($schedule, $validated, $assetIds): array {
            $schedule->update([
                'asset_id' => array_shift($assetIds),
                'technician_id' => $validated['technician_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'status' => $validated['status'],
            ]);

            $createdSchedules = [];
            foreach ($assetIds as $assetId) {
                $createdSchedules[] = MaintenanceSchedule::create([
                    'asset_id' => $assetId,
                    'technician_id' => $validated['technician_id'],
                    'scheduled_date' => $validated['scheduled_date'],
                    'status' => 'PENDIENTE',
                ]);
            }
            return $createdSchedules;
        });

        $technician = User::findOrFail($validated['technician_id']);
        if ($assignmentChanged) {
            $technician->notify(new MaintenanceAssignedNotification($schedule->fresh()->load('asset')));
        }
        foreach ($additionalSchedules as $assignedSchedule) {
            $technician->notify(new MaintenanceAssignedNotification($assignedSchedule->load('asset')));
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Agenda actualizada y nuevos equipos procesados.');
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
