<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    protected $fillable = ['asset_id', 'name', 'version'];

    
    public function asset()
{
    return $this->belongsTo(Asset::class); // El software pertenece a un equipo
}
}

