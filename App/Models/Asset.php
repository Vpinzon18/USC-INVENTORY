<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $table = 'assets';

    protected $fillable = [
        'room_id',        // <--- MUY IMPORTANTE: Añadir esto para permitir la asignación de salones
        'serial_number', 
        'hostname', 
        'ip_address', 
        'cpu', 
        'ram', 
        'storage', 
        'last_seen_at'
    ];

    /**
     * Obtiene el salón al que pertenece el equipo.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}