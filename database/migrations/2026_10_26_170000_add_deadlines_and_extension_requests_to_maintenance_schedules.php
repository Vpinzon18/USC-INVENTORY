<?php

use App\Services\MaintenanceDeadlineCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table): void {
            $table->timestampTz('assigned_at')->nullable();
            $table->timestampTz('deadline_at')->nullable();
        });

        $calculator = app(MaintenanceDeadlineCalculator::class);
        DB::table('maintenance_schedules')->select(['id', 'created_at'])->orderBy('id')->chunk(250, function ($rows) use ($calculator): void {
            foreach ($rows as $row) {
                $assignedAt = CarbonImmutable::parse($row->created_at ?? now());
                DB::table('maintenance_schedules')->where('id', $row->id)->update([
                    'assigned_at' => $assignedAt,
                    'deadline_at' => $calculator->addBusinessDays($assignedAt, 3),
                ]);
            }
        });

        Schema::create('maintenance_schedule_extension_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('maintenance_schedule_id')->constrained('maintenance_schedules')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->restrictOnDelete();
            $table->timestampTz('original_deadline_at');
            $table->timestampTz('requested_deadline_at');
            $table->text('justification');
            $table->string('status', 20)->default('PENDIENTE');
            $table->foreignId('decision_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('decision_note')->nullable();
            $table->timestampTz('approved_deadline_at')->nullable();
            $table->timestampTz('decided_at')->nullable();
            $table->timestampsTz();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedule_extension_requests');

        Schema::table('maintenance_schedules', function (Blueprint $table): void {
            $table->dropColumn(['assigned_at', 'deadline_at']);
        });
    }
};
