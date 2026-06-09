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
        'job_title_id',    // <-- Muy importante
        'dependency_id'
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
}
