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

        'health',
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
        'is_charging' => 'boolean',
        'estimated_runtime_minutes' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}