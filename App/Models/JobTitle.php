<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    // Esta línea permite guardar el nombre desde el formulario
    protected $fillable = ['name'];

    public function custodians()
    {
        return $this->hasMany(Custodian::class);
    }
}