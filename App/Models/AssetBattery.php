<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetBattery extends Model
{
    protected $fillable = [
        'asset_id',

        'manufacturer',
        'model',
        'serial_number',

        'health_percent',
        'cycle_count',

        'design_capacity',
        'full_charge_capacity',
        'remaining_capacity',

        'charge_percent',

        'is_charging',

        'estimated_runtime_minutes',
    ];

    protected $casts = [
        'cycle_count' => 'integer',
        'design_capacity' => 'integer',
        'full_charge_capacity' => 'integer',
        'remaining_capacity' => 'integer',
        'charge_percent' => 'integer',
        'estimated_runtime_minutes' => 'integer',
        'is_charging' => 'boolean',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}