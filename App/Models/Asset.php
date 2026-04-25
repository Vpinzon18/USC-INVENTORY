<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    // Asegúrate de que esta línea termine en punto y coma (;)
    protected $table = 'assets';

    // Asegúrate de que esta línea también termine en punto y coma (;)
    protected $fillable = [
        'serial_number', 
        'hostname', 
        'ip_address', 
        'cpu', 
        'ram', 
        'storage', 
        'last_seen_at'
    ];

    // Si tu tabla no tiene created_at/updated_at, descomenta la siguiente línea:
    // public $timestamps = false; 
}