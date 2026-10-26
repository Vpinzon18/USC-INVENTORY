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
        $limit = now()->subMinutes(Asset::ONLINE_THRESHOLD_MINUTES);

        $updatedOnline = Asset::where('is_agent_managed', true)
            ->whereNotNull('agent_last_seen')
            ->where('agent_last_seen', '>=', $limit)
            ->where('is_online', false)
            ->update([
                'is_online' => true,
            ]);

        $updatedOffline = Asset::where('is_agent_managed', true)
            ->where(function ($query) use ($limit) {
                $query->whereNull('agent_last_seen')
                    ->orWhere('agent_last_seen', '<', $limit);
            })
            ->where('is_online', true)
            ->update([
                'is_online' => false,
            ]);

        $this->info("Agentes marcados como online: {$updatedOnline}; offline: {$updatedOffline}.");

        return Command::SUCCESS;
    }
}
