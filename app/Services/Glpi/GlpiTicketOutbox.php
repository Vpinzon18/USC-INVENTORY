<?php

namespace App\Services\Glpi;

use App\Jobs\SyncGlpiTicket;
use App\Models\GlpiTicketSync;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GlpiTicketOutbox
{
    /** Persist the source event once, then hand it to the queue after commit. */
    public function enqueue(string $eventType, Model $source, array $payload, ?string $stableSourceId = null): ?GlpiTicketSync
    {
        if (!config('services.glpi.enabled')) {
            return null;
        }

        $sourceId = $stableSourceId ?? (string) $source->getKey();
        $sourceType = $source instanceof \App\Models\Assignment ? 'movement_act' : $source::class;
        $eventKey = hash('sha256', implode(':', [$eventType, $sourceType, $sourceId]));

        $sync = GlpiTicketSync::firstOrCreate(
            ['event_key' => $eventKey],
            [
                'event_type' => $eventType,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'payload' => $payload,
                'status' => 'pending',
            ],
        );

        if ($sync->wasRecentlyCreated) {
            SyncGlpiTicket::dispatch($sync->id)->afterCommit();
        } elseif ($sync->status === 'synced' && $sync->glpi_ticket_id) {
            Log::debug('GLPI outbox duplicate source ignored', ['sync_id' => $sync->id, 'event_type' => $eventType]);
        }

        return $sync;
    }
}
