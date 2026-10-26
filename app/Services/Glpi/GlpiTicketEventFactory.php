<?php

namespace App\Services\Glpi;

use App\Models\Assignment;
use App\Models\MaintenanceSchedule;
use App\Models\TechnicalService;
use Illuminate\Support\Facades\Log;

class GlpiTicketEventFactory
{
    public function __construct(private GlpiTicketOutbox $outbox)
    {
    }

    public function technicalService(TechnicalService $service): void
    {
        $service->loadMissing(['asset.room.building', 'technician', 'maintenanceSchedule']);
        $asset = $service->asset;
        $technician = $service->technician;
        $ref = 'BT-'.$service->getKey();

        $this->outbox->enqueue('technical_service', $service, [
            'event_type' => 'technical_service',
            'title' => "SIGMA | Bitácora técnica {$ref} | ".($asset?->internal_code ?: $asset?->serial_number ?: 'Activo'),
            'description' => implode("\n", array_filter([
                "Referencia SIGMA: {$ref}",
                'Servicio: Bitácora técnica de equipo',
                'Activo: '.($asset?->internal_code ?: 'Sin placa'),
                'Serial: '.($asset?->serial_number ?: 'Sin serial'),
                'Hostname: '.($asset?->hostname ?: 'Sin hostname'),
                'Ubicación: '.$this->location($asset),
                'Tipo de servicio: '.($service->type ?: 'Sin tipo'),
                'Fecha de atención: '.($service->performed_at ?: $service->created_at?->toDateTimeString()),
                'Técnico: '.($technician?->name ?: 'No identificado'),
                $service->maintenance_schedule_id ? 'Cronograma SIGMA: '.$service->maintenance_schedule_id : null,
                '',
                'Detalle reportado:',
                trim(strip_tags((string) $service->description)),
            ])),
            'requester_email' => $technician?->email,
            'assignee_email' => $technician?->email,
        ]);
    }

    public function movement(string $actaNumber): void
    {
        $assignments = Assignment::with(['asset.room.building', 'custodian', 'room', 'user'])
            ->where('acta_number', $actaNumber)
            ->orderBy('id')
            ->get();

        if ($assignments->isEmpty()) {
            Log::warning('No se generó ticket GLPI para un movimiento sin registros', ['acta' => $actaNumber]);
            return;
        }

        $first = $assignments->first();
        $actor = $first->user;
        $assets = $assignments->map(function (Assignment $assignment): string {
            $asset = $assignment->asset;
            return implode(' | ', array_filter([
                $asset?->internal_code ?: 'Sin placa',
                $asset?->serial_number ?: 'Sin serial',
                $asset?->hostname ?: null,
            ]));
        })->all();

        $this->outbox->enqueue('asset_movement', $first, [
            'event_type' => 'asset_movement',
            'title' => "SIGMA | Movimiento {$actaNumber} | {$assignments->count()} activo(s)",
            'description' => implode("\n", [
                "Referencia SIGMA: {$actaNumber}",
                'Servicio: Movimiento de activos',
                'Tipo: '.($first->movement_type ?: 'No especificado'),
                'Acta: '.$actaNumber,
                'Fecha: '.($first->started_at ?: $first->created_at?->toDateTimeString()),
                'Registrado por: '.($actor?->name ?: 'No identificado'),
                'Custodio destino: '.($first->custodian?->full_name ?: 'No asignado'),
                'Ubicación destino: '.$this->assignmentLocation($first),
                'Activos incluidos ('.$assignments->count().'):',
                ...$assets,
                '',
                'Observaciones:',
                trim(strip_tags((string) $first->observations)) ?: 'Sin observaciones',
            ]),
            'requester_email' => $actor?->email,
            'assignee_email' => null,
        ], $actaNumber);
    }

    public function maintenanceSchedule(MaintenanceSchedule $schedule): void
    {
        $schedule->loadMissing(['asset.room.building', 'technician']);
        $asset = $schedule->asset;
        $technician = $schedule->technician;
        $ref = 'MP-'.$schedule->getKey();

        $this->outbox->enqueue('maintenance_schedule', $schedule, [
            'event_type' => 'maintenance_schedule',
            'title' => "SIGMA | Mantenimiento programado {$ref} | ".($asset?->internal_code ?: $asset?->serial_number ?: 'Activo'),
            'description' => implode("\n", [
                "Referencia SIGMA: {$ref}",
                'Servicio: Mantenimiento preventivo programado',
                'Fecha programada: '.$schedule->scheduled_date,
                'Estado SIGMA: '.$schedule->status,
                'Activo: '.($asset?->internal_code ?: 'Sin placa'),
                'Serial: '.($asset?->serial_number ?: 'Sin serial'),
                'Hostname: '.($asset?->hostname ?: 'Sin hostname'),
                'Ubicación: '.$this->location($asset),
                'Técnico asignado: '.($technician?->name ?: 'No identificado'),
            ]),
            'requester_email' => $technician?->email,
            'assignee_email' => $technician?->email,
        ]);
    }

    private function location($asset): string
    {
        $room = $asset?->room;
        return implode(' / ', array_filter([
            $room?->building?->campus?->name,
            $room?->building?->name,
            $room?->nomenclatura ?: $room?->name,
        ])) ?: 'Sin ubicación registrada';
    }

    private function assignmentLocation(Assignment $assignment): string
    {
        $room = $assignment->room;
        return implode(' / ', array_filter([
            $room?->building?->campus?->name,
            $room?->building?->name,
            $room?->nomenclatura ?: $room?->name,
        ])) ?: 'Sin ubicación registrada';
    }
}
