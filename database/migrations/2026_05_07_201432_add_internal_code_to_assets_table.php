<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para añadir la columna de placa.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Creamos la columna internal_code (Placa USC)
            // Es nullable para no chocar con datos existentes
            // Es unique para evitar duplicados de inventario
            $table->string('internal_code')->nullable()->unique()->after('serial_number');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Eliminamos la columna si se hace un rollback
            $table->dropColumn('internal_code');
        });
    }
};