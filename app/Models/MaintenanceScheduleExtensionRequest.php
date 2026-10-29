<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceScheduleExtensionRequest extends Model
{
    protected $fillable = [
        'maintenance_schedule_id',
        'technician_id',
        'original_deadline_at',
        'requested_deadline_at',
        'justification',
        'status',
        'decision_by',
        'decision_note',
        'approved_deadline_at',
        'decided_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_deadline_at' => 'datetime',
            'original_deadline_at' => 'datetime',
            'approved_deadline_at' => 'datetime',
            'decided_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'maintenance_schedule_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decision_by');
    }
}
