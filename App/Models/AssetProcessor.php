<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetProcessor extends Model
{
    protected $table = 'asset_processors';

    protected $fillable = [
        'asset_id',
        'brand',
        'model',
        'processor_id',
        'socket',
        'status',
        'cores',
        'threads',
        'speed_mhz',
        'architecture',
        'family',
        'generation',
    ];

    protected $casts = [
        'cores' => 'integer',
        'threads' => 'integer',
        'speed_mhz' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}