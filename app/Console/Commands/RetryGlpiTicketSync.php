<?php

namespace App\Console\Commands;

use App\Jobs\SyncGlpiTicket;
use App\Models\GlpiTicketSync;
use Illuminate\Console\Command;

class RetryGlpiTicketSync extends Command
{
    protected $signature = 'sigma:glpi-retry {sync_id : ID local de glpi_ticket_syncs}';

    protected $description = 'Reencola un caso GLPI fallido después de revisar que no exista ya en GLPI';

    public function handle(): int
    {
        if (!config('services.glpi.enabled')) {
            $this->error('La integración GLPI está deshabilitada.');
            return self::FAILURE;
        }

        $sync = GlpiTicketSync::find($this->argument('sync_id'));
        if (!$sync) {
            $this->error('No existe esa sincronización.');
            return self::FAILURE;
        }
        if ($sync->status === 'synced' || $sync->glpi_ticket_id) {
            $this->error('El ticket ya está vinculado; no se reintentó para evitar duplicados.');
            return self::FAILURE;
        }
        if ($sync->status !== 'failed') {
            $this->error('Solo se pueden reintentar registros con estado failed.');
            return self::FAILURE;
        }

        $this->warn('Confirma primero en GLPI que no exista la referencia: '.$sync->source_id);
        if (!$this->confirm('¿Reencolar el caso?')) {
            $this->comment('No se hicieron cambios.');
            return self::SUCCESS;
        }

        $sync->update(['status' => 'pending', 'attempts' => 0, 'last_error' => null]);
        SyncGlpiTicket::dispatch($sync->id)->afterCommit();
        $this->info('Caso reencolado. Revisa glpi_ticket_syncs para confirmar el resultado.');

        return self::SUCCESS;
    }
}
