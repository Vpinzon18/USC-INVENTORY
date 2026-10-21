<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SigmaAgentEnrollmentCode extends Model
{
    protected $fillable = [
        'code_hash',
        'created_by_user_id',
        'asset_id',
        'expires_at',
        'consumed_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
