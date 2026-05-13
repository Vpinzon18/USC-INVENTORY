<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalService extends Model
{
    protected $fillable = [
    'asset_id',
    'user_id',
    'performed_at', // Este es el que acabamos de crear con la migración
    'type',
    'description'
];

    // Para saber a qué equipo pertenece este mantenimiento
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // Para saber qué técnico realizó el trabajo
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}