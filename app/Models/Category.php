<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];

    /**
     * Relación: Una categoría tiene muchos activos asociados.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'category_id');
    }
}