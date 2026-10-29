<?php

namespace App\Console\Commands;

use App\Models\MaintenanceSchedule;
use Illuminate\Console\Command;

class MarkExpiredMaintenanceSchedules extends Command
{
    protected $signature = 'schedules:mark-overdue';

    protected $description = 'Marca como vencidas las agendas preventivas que superaron su fecha límite.';

    public function handle(): int
    {
        $updated = MaintenanceSchedule::query()
            ->where('status', 'PENDIENTE')
            ->whereNotNull('deadline_at')
            ->where('deadline_at', '<=', now())
            ->update(['status' => 'VENCIDO', 'updated_at' => now()]);

        $this->info("Agendas marcadas como vencidas: {$updated}.");

        return self::SUCCESS;
    }
}
