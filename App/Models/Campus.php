<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    protected $fillable = ['name'];

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }
    public function assets()
    {

        return Asset::whereHas('room.building', function ($query) {
            $query->where('campus_id', $this->id);
        });
    }
}
