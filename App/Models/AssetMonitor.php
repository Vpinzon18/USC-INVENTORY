<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMonitor extends Model
{
    protected $fillable = [
        'asset_id',
        'brand',
        'model',
        'serial_number',
        'manufacturer_code',
        'year',
        'size',
        'resolution',
        'refresh_rate',
    ];

    protected $casts = [
        'year' => 'integer',
        'refresh_rate' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}