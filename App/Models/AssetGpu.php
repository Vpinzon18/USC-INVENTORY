<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetGpu extends Model
{
    protected $table = 'asset_gpus';

    protected $fillable = [
        'asset_id',
        'brand',
        'model',
        'memory_mb',
        'driver_version',
        'processor',
        'resolution',
        'refresh_rate',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}