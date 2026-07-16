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
        Schema::table('rooms', function (Blueprint $table) {
            // 1. Tipo de Espacio (Oficina, Laboratorio, Salón de Clase, Auditorio)
            $table->string('type')->default('Oficina')->after('name'); 
            
            // 2. Estado Operativo (Activa, Inactiva, En Remodelación)
            $table->string('status')->default('Activo')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['type', 'status']);
        });
    }
};