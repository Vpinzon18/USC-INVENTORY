<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHistory extends Model
{
    protected $table = 'asset_history';

    protected $fillable = [
        'asset_id',
        'field_name',
        'old_value',
        'new_value',
        'detected_at',
        'source',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    public $timestamps = false;

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}