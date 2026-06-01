<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\technicalServices;
class Asset extends Model
{
    protected $table = 'assets';

   protected $fillable = [
    // Identificación
    'hostname', 'serial_number', 'internal_code', 'mac_address', 'ip_address',
    
    // Relaciones
    'sede_id', 'room_id', 'custodian_id',
    
    // Hardware Desglosado (Los que quieres usar de aquí en adelante)
    'cpu_brand', 'cpu_model',
    'storage_brand', 'storage_model',
    'gpu_brand', 'gpu_model',
    'wifi_brand', 'wifi_model',
    'board_brand', 'board_model', // Asegúrate de tener estas creadas en BD
    
    // Otros
    'ram','ram_brand', 'ram_model', 'ram_capacity_gb', 'os_version', 'domain_name', 'security_guaya',
    'monitor_asset', 'monitor_serial', 'keyboard_serial', 'mouse_serial',
    'last_seen_at'
];
// En tu modelo Asset.php
public function getCpuAttribute()
{
    return "{$this->cpu_brand} {$this->cpu_model}";
}

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
    return $this->hasOne(Assignment::class)
                ->where('status', 'active')
                ->latestOfMany(); // Asegura que solo tome la más reciente
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
    /**
 * HISTORIAL COMPLETO DE ASIGNACIONES
 * Cambiamos 'history' por 'assignments' para que el controlador lo encuentre.
 */
public function assignments(): HasMany
{
    return $this->hasMany(Assignment::class)->orderBy('created_at', 'desc');
}
public function technicalServices(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(TechnicalService::class)->orderBy('created_at', 'desc');
}

public function custodian()
{
    // Un equipo pertenece a un responsable (custodio)
    return $this->belongsTo(Custodian::class);
}
    

public function maintenances()
{
    // Un equipo (Asset) puede tener muchos mantenimientos/servicios técnicos
    // Asegúrate de importar el modelo si es necesario, o usa la ruta completa si tu editor lo pide
    return $this->hasMany(TechnicalService::class, 'asset_id'); 
}

public function maintenanceSchedules()
{
    return $this->hasMany(MaintenanceSchedule::class);
}


public function software() 
{
    return $this->hasMany(\App\Models\Software::class);
}
}