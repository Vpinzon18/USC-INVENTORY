<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
   // app/Models/Room.php

protected $fillable = [
    'name',
    'nomenclatura', // Nuevo campo
    'building_id',
    'floor',
];

    // Relación con los equipos (Un salón tiene muchos equipos)
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    // Relación inversa con el Bloque
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
    public function rooms()
{
    return $this->belongsToMany(Room::class, 'custodian_room');
}
    
}