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
    Schema::create('technical_services', function (Blueprint $table) {
        $table->id();
        // Relación con el equipo
        $table->foreignId('asset_id')->constrained()->onDelete('cascade');
        // Relación con el técnico (asumiendo que usas la tabla users para los técnicos)
        $table->foreignId('user_id')->constrained()->onDelete('restrict');
        
        // Campos del formato
        $table->string('type'); // Ejemplo: Mantenimiento Preventivo, Correctivo, etc.
        $table->text('description'); // El campo amplio de "Descripción"
        
        $table->timestamps(); // Esto nos dará automáticamente la fecha (created_at)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_services');
    }
};
