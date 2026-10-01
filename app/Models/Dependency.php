<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependency extends Model
{

protected $fillable = ['name'];
    public function custodians()
{
    
    return $this->hasMany(Custodian::class);
}
}
