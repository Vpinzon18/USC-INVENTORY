<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RemoteSupportSession extends Model
{
    protected $fillable = [
        'requested_by_user_id', 'source_asset_id', 'target_asset_id',
        'request_hash', 'grant_hash', 'source_rustdesk_id', 'status', 'scopes',
        'client_session_id', 'requested_ip', 'source_ip', 'target_ip', 'close_reason', 'expires_at',
        'authorized_at', 'started_at', 'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'scopes' => 'array',
            'expires_at' => 'immutable_datetime',
            'authorized_at' => 'immutable_datetime',
            'started_at' => 'immutable_datetime',
            'ended_at' => 'immutable_datetime',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function sourceAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'source_asset_id');
    }

    public function targetAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'target_asset_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(RemoteSupportSessionEvent::class);
    }
}
