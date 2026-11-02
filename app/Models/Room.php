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
        'dependency_id',
        'room_type_id', 
        'floor',
        'status',
    ];
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
    public function dependency(): BelongsTo
    {
        return $this->belongsTo(Dependency::class);
    }
      public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

}
