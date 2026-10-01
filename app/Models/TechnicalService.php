<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalService extends Model
{
    protected $fillable = [
        'asset_id',
        'user_id',
        'performed_at',
        'type',
        'description',
        'maintenance_schedule_id'

    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function maintenanceSchedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }
}
