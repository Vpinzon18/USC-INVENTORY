<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Agregamos la columna 'internal_code' después de 'serial_number'
            // La ponemos como nullable por si ya tienes equipos registrados sin placa
            $table->string('internal_code')->nullable()->unique()->after('serial_number');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Esto permite revertir el cambio si algo sale mal
            $table->dropColumn('internal_code');
        });
    }
};