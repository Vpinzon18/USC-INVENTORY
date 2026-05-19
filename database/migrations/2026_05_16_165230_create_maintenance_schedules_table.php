<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            // Vinculamos de forma obligatoria al equipo
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            // Cuándo se planifica realizar físicamente la intervención
            $table->date('scheduled_date'); 
            // Estado actual del compromiso en el semestre
            $table->enum('status', ['PENDIENTE', 'REALIZADO', 'VENCIDO'])->default('PENDIENTE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};