<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    protected $fillable = [
    'asset_id',
    'name',
    'version',
    'publisher',
    'install_date',
    'install_location',
    'estimated_size_mb',
    'architecture',
    'is_active',
];


    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
