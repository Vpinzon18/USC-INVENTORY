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
        'observations',
        'movement_type',
        'headquarters',
        'acta_number',
        'user_id'
    ];
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function custodian()
    {
        return $this->belongsTo(Custodian::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    // Relación hacia el Técnico / Usuario que registró el movimiento en el sistema
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
        // Nota: Si en tu base de datos la columna se llama 'technician_id', cámbialo aquí arriba.
    }
}
