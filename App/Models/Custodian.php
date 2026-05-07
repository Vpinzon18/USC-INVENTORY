<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Custodian.php
class Custodian extends Model
{
    protected $fillable = [
        'full_name', 'document_number', 'dependency', 
        'job_title', 'email', 'extension'
    ];

    // Un custodio puede ser responsable de muchos equipos
    public function assets()
    {
        return $this->hasMany(Asset::class); // Suponiendo que tu modelo de equipo se llama Asset
    }
}
