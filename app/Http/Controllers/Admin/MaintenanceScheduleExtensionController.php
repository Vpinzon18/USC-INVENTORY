<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceScheduleExtensionRequest;
use App\Models\User;
use App\Notifications\MaintenanceExtensionNotification;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class MaintenanceScheduleExtensionController extends Controller
{
    public function create(MaintenanceSchedule $schedule)
    {
        $this->authorizeTechnician($schedule);
        abort_if($schedule->status === 'REALIZADO' || $schedule->technicalService()->exists(), 409, 'El mantenimiento ya fue realizado.');

        $pendingRequest = $schedule->extensionRequests()->where('status', 'PENDIENTE')->exists();
        abort_if($pendingRequest, 409, 'Ya existe una solicitud de ampliación pendiente para esta agenda.');

        return view('admin.schedules.extensions.create', compact('schedule'));
    }

    public function store(Request $request, MaintenanceSchedule $schedule)
    {
        $this->authorizeTechnician($schedule);

        $validated = $request->validate([
            'requested_deadline_at' => ['required', 'date'],
            'justification' => ['required', 'string', 'min:20', 'max:3000'],
        ], [
            'requested_deadline_at.required' => 'Indica la nueva fecha y hora que solicitas.',
            'justification.required' => 'La justificación es obligatoria.',
            'justification.min' => 'Explica el motivo con al menos 20 caracteres.',
        ]);

        DB::transaction(function () use ($schedule, $validated): void {
            $lockedSchedule = MaintenanceSchedule::query()->lockForUpdate()->findOrFail($schedule->id);
            $this->authorizeTechnician($lockedSchedule);
            if ($lockedSchedule->status === 'REALIZADO' || $lockedSchedule->technicalService()->exists()) {
                throw ValidationException::withMessages(['schedule' => 'El mantenimiento ya fue realizado.']);
            }
            if ($lockedSchedule->extensionRequests()->where('status', 'PENDIENTE')->exists()) {
                throw ValidationException::withMessages(['schedule' => 'Ya existe una solicitud de ampliación pendiente.']);
            }

            $currentDeadline = $lockedSchedule->deadline_at ? CarbonImmutable::parse($lockedSchedule->deadline_at) : null;
            $requestedDeadline = CarbonImmutable::parse($validated['requested_deadline_at']);
            if (!$currentDeadline || $requestedDeadline->lessThanOrEqualTo($currentDeadline) || $requestedDeadline->lessThanOrEqualTo(now())) {
                throw ValidationException::withMessages([
                    'requested_deadline_at' => 'La ampliación debe ser posterior al vencimiento actual y a la fecha y hora presentes.',
                ]);
            }

            $lockedSchedule->extensionRequests()->create([
                'technician_id' => Auth::id(),
                'original_deadline_at' => $currentDeadline,
                'requested_deadline_at' => $requestedDeadline,
                'justification' => trim($validated['justification']),
                'status' => 'PENDIENTE',
            ]);
        });

        $assetLabel = $schedule->asset?->internal_code ?: $schedule->asset?->serial_number ?: 'Agenda #'.$schedule->id;
        User::where('role', 1)->get()->each(fn (User $admin) => $admin->notify(new MaintenanceExtensionNotification(
            'Nueva solicitud de ampliación',
            "El técnico ".Auth::user()->name." solicita ampliar el plazo de {$assetLabel}.",
            route('admin.schedules.extensions.index'),
            (int) $schedule->id,
        )));

        return redirect()->route('schedules.index')->with('success', 'Solicitud enviada. El plazo seguirá igual hasta que un administrador la apruebe.');
    }

    public function index(Request $request)
    {
        $query = MaintenanceScheduleExtensionRequest::with([
            'schedule.asset.room.building',
            'schedule.technician',
            'technician',
            'decider',
        ]);

        if ($request->filled('status') && in_array($request->status, ['PENDIENTE', 'APROBADA', 'RECHAZADA'], true)) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderByRaw("CASE WHEN status = 'PENDIENTE' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $pendingCount = MaintenanceScheduleExtensionRequest::where('status', 'PENDIENTE')->count();

        return view('admin.schedules.extensions.index', compact('requests', 'pendingCount'));
    }

    public function approve(Request $request, MaintenanceScheduleExtensionRequest $extensionRequest)
    {
        $validated = $request->validate([
            'approved_deadline_at' => ['required', 'date'],
            'decision_note' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($extensionRequest, $validated): void {
            $extensionRequest = MaintenanceScheduleExtensionRequest::query()->lockForUpdate()->findOrFail($extensionRequest->id);
            if ($extensionRequest->status !== 'PENDIENTE') {
                throw ValidationException::withMessages(['extension' => 'Esta solicitud ya fue resuelta.']);
            }

            $schedule = MaintenanceSchedule::query()->lockForUpdate()->findOrFail($extensionRequest->maintenance_schedule_id);
            if ($schedule->status === 'REALIZADO' || $schedule->technicalService()->exists()) {
                throw ValidationException::withMessages(['extension' => 'No se puede ampliar una agenda ya realizada.']);
            }

            $approvedDeadline = CarbonImmutable::parse($validated['approved_deadline_at']);
            $currentDeadline = $schedule->deadline_at ? CarbonImmutable::parse($schedule->deadline_at) : null;
            if (!$currentDeadline || $approvedDeadline->lessThanOrEqualTo($currentDeadline) || $approvedDeadline->lessThanOrEqualTo(now())) {
                throw ValidationException::withMessages(['approved_deadline_at' => 'El plazo aprobado debe ser futuro y posterior al límite vigente.']);
            }

            $extensionRequest->update([
                'status' => 'APROBADA',
                'decision_by' => Auth::id(),
                'decision_note' => $validated['decision_note'] ?? null,
                'approved_deadline_at' => $approvedDeadline,
                'decided_at' => now(),
            ]);
            $schedule->update([
                'deadline_at' => $approvedDeadline,
                'status' => 'PENDIENTE',
            ]);
        });

        $extensionRequest->loadMissing('technician', 'schedule.asset');
        $assetLabel = $extensionRequest->schedule?->asset?->internal_code ?: $extensionRequest->schedule?->asset?->serial_number ?: 'Agenda #'.$extensionRequest->maintenance_schedule_id;
        $extensionRequest->technician?->notify(new MaintenanceExtensionNotification(
            'Ampliación aprobada',
            "Se aprobó el nuevo plazo para {$assetLabel}: ".CarbonImmutable::parse($validated['approved_deadline_at'])->format('d/m/Y H:i').'.',
            route('schedules.index'),
            (int) $extensionRequest->maintenance_schedule_id,
        ));

        return back()->with('success', 'Ampliación aprobada y fecha límite actualizada en SIGMA.');
    }

    public function reject(Request $request, MaintenanceScheduleExtensionRequest $extensionRequest)
    {
        $validated = $request->validate([
            'decision_note' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'decision_note.required' => 'Indica el motivo del rechazo para informar al técnico.',
            'decision_note.min' => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $updated = MaintenanceScheduleExtensionRequest::query()
            ->whereKey($extensionRequest->id)
            ->where('status', 'PENDIENTE')
            ->update([
                'status' => 'RECHAZADA',
                'decision_by' => Auth::id(),
                'decision_note' => trim($validated['decision_note']),
                'decided_at' => now(),
                'updated_at' => now(),
            ]);

        if (!$updated) {
            throw ValidationException::withMessages(['extension' => 'Esta solicitud ya fue resuelta.']);
        }

        $extensionRequest->loadMissing('technician', 'schedule.asset');
        $assetLabel = $extensionRequest->schedule?->asset?->internal_code ?: $extensionRequest->schedule?->asset?->serial_number ?: 'Agenda #'.$extensionRequest->maintenance_schedule_id;
        $extensionRequest->technician?->notify(new MaintenanceExtensionNotification(
            'Ampliación rechazada',
            "Se rechazó la solicitud para {$assetLabel}. Motivo: ".trim($validated['decision_note']),
            route('schedules.index'),
            (int) $extensionRequest->maintenance_schedule_id,
        ));

        return back()->with('success', 'Solicitud rechazada; se guardó el motivo para consulta del equipo administrativo.');
    }

    private function authorizeTechnician(MaintenanceSchedule $schedule): void
    {
        abort_unless((int) Auth::user()->role === 2, 403);
        abort_unless((int) $schedule->technician_id === (int) Auth::id(), 403);
    }
}
