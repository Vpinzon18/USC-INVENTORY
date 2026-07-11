<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    // Habilitamos todos los campos necesarios para la asignación masiva
    protected $fillable = [
        'name',
        'city',
        'type',
        'address',
        'status',
    ];

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }
    
    public function rooms()
    {
        // Pasa a través de Building para llegar a Room
        return $this->hasManyThrough(Room::class, Building::class);
    }
    
    public function assets()
    {
        return Asset::whereHas('room.building', function ($query) {
            $query->where('campus_id', $this->id);
        });
    }
}