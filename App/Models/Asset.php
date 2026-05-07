<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Asset extends Model
{
    protected $table = 'assets';

    protected $fillable = [
        'room_id', 
        'serial_number', 
        'internal_code',
        'hostname', 
        'ip_address', 
        'cpu', 
        'ram', 
        'storage', 
        'last_seen_at'
    ];

    /**
     * Relación directa con el salón actual (Ubicación física)
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * OBTIENE LA ASIGNACIÓN ACTIVA
     * Útil para cerrar procesos de entrega y ver quién responde hoy.
     */
    public function currentAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }

    /**
     * QUIÉN RESPONDE (Persona)
     * Acceso directo al Custodio actual a través de la asignación activa.
     */
    public function currentCustodian(): HasOneThrough
    {
        return $this->hasOneThrough(
            Custodian::class, 
            Assignment::class,
            'asset_id',        // Llave foránea en assignments
            'id',              // Llave foránea en custodians
            'id',              // Llave local en assets
            'custodian_id'     // Llave local en assignments
        )->where('assignments.status', 'active');
    }

    /**
     * HISTORIAL COMPLETO
     * Para la auditoría de la hoja de vida.
     */
    public function history(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderBy('created_at', 'desc');
    }

    
}