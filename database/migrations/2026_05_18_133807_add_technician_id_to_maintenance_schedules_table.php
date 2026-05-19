<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('maintenance_schedules', function (Blueprint $table) {
        // Añadimos el técnico asignado (apunta a la tabla users)
        $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('maintenance_schedules', function (Blueprint $table) {
        $table->dropForeign(['technician_id']);
        $table->dropColumn('technician_id');
    });
}
};
