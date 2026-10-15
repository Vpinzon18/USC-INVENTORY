<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->latest()->paginate(15);
        $scheduleIds = $notifications->getCollection()
            ->map(fn ($notification) => data_get($notification->data, 'maintenance_schedule_id'))
            ->filter()
            ->unique()
            ->values();
        $schedules = MaintenanceSchedule::query()
            ->whereIn('id', $scheduleIds)
            ->get(['id', 'asset_id', 'technician_id'])
            ->keyBy('id');

        $notifications->getCollection()->transform(function ($notification) use ($schedules, $request) {
            $data = $notification->data ?? [];
            $schedule = $schedules->get($data['maintenance_schedule_id'] ?? null);

            if ($schedule && ((int) $request->user()->role !== 2 || (int) $schedule->technician_id === (int) $request->user()->id)) {
                $data['url'] = route('maintenances.create', [
                    'asset_id' => $schedule->asset_id,
                    'schedule_id' => $schedule->id,
                ]);
            }

            $notification->data = $data;

            return $notification;
        });

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $request->user()->notifications()->whereKey($notification)->firstOrFail()->markAsRead();

        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Todas las notificaciones quedaron marcadas como leídas.');
    }
}
