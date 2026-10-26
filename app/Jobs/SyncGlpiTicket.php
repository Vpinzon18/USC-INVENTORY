<?php

namespace App\Jobs;

use App\Models\GlpiTicketSync;
use App\Services\Glpi\GlpiApiClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SyncGlpiTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;
    public int $timeout = 45;

    public function __construct(public int $syncId)
    {
    }

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function handle(GlpiApiClient $client): void
    {
        $sync = GlpiTicketSync::find($this->syncId);
        if (!$sync || $sync->status === 'synced') {
            return;
        }

        $sync->update(['status' => 'processing', 'attempts' => $sync->attempts + 1, 'last_error' => null]);

        try {
            $ticketId = $client->createTicket($sync->payload);
            $sync->update([
                'status' => 'synced',
                'glpi_ticket_id' => $ticketId,
                'synced_at' => now(),
                'last_error' => null,
            ]);
            Log::info('SIGMA creó un caso GLPI', [
                'sync_id' => $sync->id,
                'event_type' => $sync->event_type,
                'glpi_ticket_id' => $ticketId,
            ]);
        } catch (Throwable $exception) {
            $finalAttempt = $this->attempts() >= $this->tries;
            $sync->update([
                'status' => $finalAttempt ? 'failed' : 'pending',
                'last_error' => Str::limit($exception->getMessage(), 1000),
            ]);
            Log::warning('Falló la sincronización de un caso SIGMA con GLPI', [
                'sync_id' => $sync->id,
                'event_type' => $sync->event_type,
                'attempt' => $this->attempts(),
                'final' => $finalAttempt,
                'error' => Str::limit($exception->getMessage(), 300),
            ]);
            throw $exception;
        }
    }

    public function failed(?Throwable $exception): void
    {
        GlpiTicketSync::whereKey($this->syncId)->where('status', '!=', 'synced')->update([
            'status' => 'failed',
            'last_error' => Str::limit($exception?->getMessage() ?? 'Error no especificado', 1000),
        ]);
    }
}
