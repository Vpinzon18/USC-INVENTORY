<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SigmaAgentEnrollmentRequest extends Model
{
    protected $fillable = [
        'request_id',
        'device',
        'public_key',
        'public_key_fingerprint',
        'request_ip',
        'status',
        'asset_id',
        'approved_by_user_id',
        'encrypted_credential',
        'approved_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'device' => 'array',
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
