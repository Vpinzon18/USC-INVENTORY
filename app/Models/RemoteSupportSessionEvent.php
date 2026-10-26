<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RemoteSupportSessionEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['remote_support_session_id', 'event', 'actor', 'details', 'created_at'];

    protected function casts(): array
    {
        return ['details' => 'array', 'created_at' => 'immutable_datetime'];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(RemoteSupportSession::class, 'remote_support_session_id');
    }
}
