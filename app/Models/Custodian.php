<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Custodian extends Model
{
    protected $fillable = [
        'full_name',
        'document_number',
        'dependency',
        'job_title',
        'email',
        'extension',
        'cost_center',
        'job_title_id',    
        'dependency_id',
        'status'
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'custodian_room');
    }
    public function dependency()
    {
        return $this->belongsTo(Dependency::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }
    
    /**
     * Relación para obtener los ACTIVOS (Equipos) asignados actualmente.
     */
    public function activeAssets()
    {
        // Esto le dice a Laravel: 
        // 1. Busca modelos Asset.
        // 2. A través de la tabla 'assignments'.
        // 3. Donde el status de la asignación sea 'active'.
        return $this->belongsToMany(\App\Models\Asset::class, 'assignments', 'custodian_id', 'asset_id')
                    ->wherePivot('status', 'active');
    }
}
