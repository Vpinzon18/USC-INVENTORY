<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{

    protected $fillable = [
        'name',
        'nomenclatura',
        'building_id',
        'floor',
    ];
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'custodian_room');
    }
}
