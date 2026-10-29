<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
    'asset_id', 
    'technician_id', 
    'scheduled_date', 
    'status',
    'assigned_at',
    'deadline_at',
    
];
    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'deadline_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
    public function technician()
{
    return $this->belongsTo(User::class, 'technician_id');
}
public function technicalService() {
    return $this->hasOne(TechnicalService::class, 'maintenance_schedule_id')->latestOfMany();
}

public function extensionRequests()
{
    return $this->hasMany(MaintenanceScheduleExtensionRequest::class);
}

public function latestExtensionRequest()
{
    return $this->hasOne(MaintenanceScheduleExtensionRequest::class)->latestOfMany();
}

}
