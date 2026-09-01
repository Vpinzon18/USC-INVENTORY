<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetRamModule extends Model
{
    protected $fillable = [
        'asset_id',
        'manufacturer',
        'model',
        'serial_number',
        'bank',
        'slot',
        'memory_type',
        'capacity_gb',
        'speed_mhz',
    ];

    protected $casts = [
        'capacity_gb' => 'integer',
        'speed_mhz' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}