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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            // Ej: "Oficina", "Laboratorio", "Auditorio"
            $table->string('name')->unique(); 
            // Ej: "Espacio destinado para la docencia" (Opcional)
            $table->string('description')->nullable(); 
            // Permite activar/desactivar tipos desde tu módulo de configuraciones
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};