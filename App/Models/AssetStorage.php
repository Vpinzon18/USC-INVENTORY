<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetStorage extends Model
{
    protected $table = 'asset_storage_devices';

    protected $fillable = [

        'asset_id',

        'brand',

        'model',

        'serial_number',

        'capacity_gb',

        'free_gb',

        'type',

        'health'

    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}