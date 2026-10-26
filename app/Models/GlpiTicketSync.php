<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlpiTicketSync extends Model
{
    protected $fillable = [
        'event_type',
        'source_type',
        'source_id',
        'event_key',
        'payload',
        'status',
        'glpi_ticket_id',
        'attempts',
        'last_error',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'synced_at' => 'datetime',
        ];
    }
}
