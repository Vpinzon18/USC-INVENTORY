<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}