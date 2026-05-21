<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
    'asset_id', 
    'technician_id', 
    'scheduled_date', 
    'status'
    
];

    /**
     * Obtener el equipo al que pertenece esta tarea programada.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
    public function technician()
{
    return $this->belongsTo(User::class, 'technician_id');
}
public function technicalService() {
    // Si la descripción está en technical_services, asegúrate que haya una relación
    return $this->hasOne(TechnicalService::class, 'maintenance_schedule_id');
}

}