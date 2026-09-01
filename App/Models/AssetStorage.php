<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetStorage extends Model
{
    protected $table = 'asset_storage_devices';

    protected $fillable = [
        'asset_id',
        'brand',
        'model',
        'serial_number',
        'firmware',
        'capacity_gb',
        'free_gb',
        'type',
        'health',
    ];

    protected $casts = [
        'capacity_gb' => 'integer',
        'free_gb' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}