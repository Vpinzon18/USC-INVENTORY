<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalService extends Model
{
    protected $fillable = [
    'asset_id',
    'user_id',
    'performed_at', // Este es el que acabamos de crear con la migración
    'type',
    'description',
    'maintenance_schedule_id'
    
];

    // Para saber a qué equipo pertenece este mantenimiento
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // Para saber qué técnico realizó el trabajo
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user(): BelongsTo
    {
        // Esto le dice a Laravel que 'user_id' en esta tabla 
        // se conecta con la tabla de Usuarios.
        return $this->belongsTo(User::class);
    }
    public function maintenanceSchedule()
{
    return $this->belongsTo(MaintenanceSchedule::class);
}
}