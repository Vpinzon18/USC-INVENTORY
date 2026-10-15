<?php

namespace App\Notifications;

use App\Models\MaintenanceSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class MaintenanceAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public MaintenanceSchedule $schedule)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $asset = $this->schedule->asset;
        $assetLabel = $asset?->internal_code ?: $asset?->serial_number ?: 'Equipo #' . $this->schedule->asset_id;
        $date = Carbon::parse($this->schedule->scheduled_date)->format('d/m/Y');

        return [
            'title' => 'Mantenimiento asignado',
            'message' => "Se te asignó un mantenimiento preventivo para {$assetLabel}, programado para el {$date}.",
            'asset_label' => $assetLabel,
            'scheduled_date' => $date,
            'maintenance_schedule_id' => $this->schedule->getKey(),
            'asset_id' => $this->schedule->asset_id,
            'url' => route('maintenances.create', ['asset_id' => $this->schedule->asset_id, 'schedule_id' => $this->schedule->getKey()]),
        ];
    }
}




