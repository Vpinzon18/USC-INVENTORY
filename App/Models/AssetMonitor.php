<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

        'refresh_rate'

    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}