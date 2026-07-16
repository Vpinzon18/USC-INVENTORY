<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Relación: Un Tipo de Espacio agrupa muchas Oficinas/Salones
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}