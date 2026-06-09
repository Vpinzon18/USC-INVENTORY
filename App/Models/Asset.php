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
        'hostname',
        'serial_number',
        'internal_code',
        'mac_address',
        'ip_address',
        'model_version',

        // Relaciones
        'sede_id',
        'room_id',
        'custodian_id',

        //Datos de Componentes
        'cpu_brand',
        'cpu_model',
        'storage_brand',
        'storage_model',
        'gpu_brand',
        'gpu_model',
        'wifi_brand',
        'wifi_model',
        'board_brand',
        'board_model',

        // Otros
        'ram',
        'ram_brand',
        'ram_model',
        'ram_capacity_gb',
        'os_version',
        'domain_name',
        'security_guaya',
        'monitor_asset',
        'monitor_serial',
        'keyboard_serial',
        'mouse_serial',
        'last_seen_at'
    ];

    public function getCpuAttribute()
    {
        return "{$this->cpu_brand} {$this->cpu_model}";
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)
            ->where('status', 'active')
            ->latestOfMany();
    }


    public function currentCustodian(): HasOneThrough
    {
        return $this->hasOneThrough(
            Custodian::class,
            Assignment::class,
            'asset_id',
            'id',
            'id',
            'custodian_id'
        )->where('assignments.status', 'active');
    }

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

        return $this->belongsTo(Custodian::class);
    }


    public function maintenances()
    {

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
