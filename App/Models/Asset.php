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

        // =========================================================
        // IDENTIFICACIÓN
        // =========================================================

        'hostname',
        'serial_number',
        'internal_code',
        'mac_address',
        'ip_address',

        // =========================================================
        // RED
        // =========================================================

        'ipv4',
        'ipv6',
        'gateway',
        'dns_server',
        'ethernet_mac',
        'wifi_mac',
        'domain_name',
        'model_version',

        // =========================================================
        // RELACIONES
        // =========================================================

        'sede_id',
        'room_id',
        'custodian_id',

        // =========================================================
        // CPU
        // =========================================================

        'cpu_brand',
        'cpu_model',
        'cpu_cores',
        'cpu_threads',
        'cpu_speed_mhz',
        'cpu_architecture',

        // =========================================================
        // STORAGE
        // =========================================================

        'storage_brand',
        'storage_serial',
        'storage_model',
        'storage_free_gb',
        'storage_type',
        'storage_health',
        'storage_capacity_gb',

        // =========================================================
        // GPU
        // =========================================================

        'gpu_brand',
        'gpu_model',

        // =========================================================
        // WIFI
        // =========================================================

        'wifi_brand',
        'wifi_model',

        // =========================================================
        // BOARD / MOTHERBOARD
        // =========================================================

        'board_brand',
        'board_model',
        'board_serial',
        'board_version',
        'board_status',

        // =========================================================
        // RAM
        // =========================================================

        'ram',
        'ram_brand',
        'ram_model',
        'ram_capacity_gb',
        'ram_speed',
        'ram_type',
        'ram_slots',
        'ram_modules',

        // =========================================================
        // SISTEMA OPERATIVO
        // =========================================================

        'windows_build',
        'windows_edition',
        'license_status',
        'secure_boot',
        'bitlocker_status',
        'tpm_version',
        'uptime_minutes',
        'windows_version',
        'antivirus',
        'firewall_enabled',
        'os_version',

        // =========================================================
        // BIOS
        // =========================================================

        'bios_version',
        'bios_date',
        'manufacturer',

        // =========================================================
        // PERIFÉRICOS / SEGURIDAD
        // =========================================================

        'security_guaya',
        'monitor_asset',
        'monitor_serial',
        'keyboard_serial',
        'mouse_serial',
        'last_seen_at',

        // =========================================================
        // USUARIO
        // =========================================================

        'logged_user',
        'last_login',

        // =========================================================
        // SIGMA AGENT
        // =========================================================

        'is_agent_managed',
        'agent_last_seen',
        'is_online',
        'agent_version',
        'last_inventory_at',
    ];

    protected $casts = [

        // =========================================================
        // FECHAS
        // =========================================================

        'last_seen_at' => 'datetime',
        'agent_last_seen' => 'datetime',
        'last_inventory_at' => 'datetime',

        // =========================================================
        // BOOLEANOS
        // =========================================================

        'is_agent_managed' => 'boolean',
        'is_online' => 'boolean',

        // =========================================================
        // JSONB
        // =========================================================

        'ram_modules' => 'array',
    ];

    // =============================================================
    // ATRIBUTOS
    // =============================================================

    public function getCpuAttribute()
    {
        return "{$this->cpu_brand} {$this->cpu_model}";
    }

    // =============================================================
    // RELACIONES
    // =============================================================

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
        return $this->hasMany(Assignment::class)
            ->orderBy('created_at', 'desc');
    }

    public function technicalServices(): HasMany
    {
        return $this->hasMany(TechnicalService::class)
            ->orderBy('created_at', 'desc');
    }

    public function custodian(): BelongsTo
    {
        return $this->belongsTo(Custodian::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(TechnicalService::class, 'asset_id');
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function software(): HasMany
    {
        return $this->hasMany(Software::class);
    }

    public function battery(): HasOne
    {
        return $this->hasOne(AssetBattery::class);
    }

    public function monitors(): HasMany
    {
        return $this->hasMany(AssetMonitor::class);
    }

    public function storageDevices(): HasMany
    {
        return $this->hasMany(AssetStorage::class);
    }

    public function ramModules(): HasMany
    {
        return $this->hasMany(AssetRamModule::class);
    }

    public function processors(): HasMany
    {
        return $this->hasMany(AssetProcessor::class);
    }

    public function gpus(): HasMany
    {
        return $this->hasMany(AssetGpu::class);
    }
}