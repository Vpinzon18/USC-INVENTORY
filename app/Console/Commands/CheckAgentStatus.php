<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;

class CheckAgentStatus extends Command
{
    protected $signature = 'agents:check-status';

    protected $description = 'Actualiza el estado online/offline de los agentes SIGMA';

    public function handle()
    {
        $limit = now()->subMinutes(10);

        $updated = Asset::whereNotNull('agent_last_seen')
            ->where(function ($query) use ($limit) {
                $query->where('agent_last_seen', '<', $limit);
            })
            ->where('is_online', true)
            ->update([
                'is_online' => false,
            ]);

        $this->info(
            "Agentes marcados como offline: {$updated}"
        );

        return Command::SUCCESS;
    }
}