<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    // 1. Nombre de la tabla en Postgres (asegúrate que sea este)
    protected $table = 'assets';

    // 2. Campos que el APK de C# puede llenar
    protected $fillable = ['serial_number', 'hostname', 'ip_address', 'cpu', 'ram', 'storage', 'last_seen_at'];

    // 3. Desactiva esto si tu tabla NO tiene las columnas created_at y updated_at
    // public $timestamps = false; 
}