<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'asset_id', 
        'custodian_id', 
        'room_id', 
        'started_at', 
        'ended_at', 
        'status', 
        'observations'
    ];

    // Relaciones para poder consultar fácilmente
    public function asset() { return $this->belongsTo(Asset::class); }
    public function custodian() { return $this->belongsTo(Custodian::class); }
    public function room() { return $this->belongsTo(Room::class); }
}