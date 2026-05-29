<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            // Campos para filtros y analítica del Dashboard
            $table->string('ram_brand')->nullable();       // Marca (ej: Kingston)
            $table->string('ram_model')->nullable();       // Modelo/Referencia
            $table->integer('ram_capacity_gb')->nullable(); // Capacidad numérica para cálculos
        });
    }

    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['ram_brand', 'ram_model', 'ram_capacity_gb']);
        });
    }
};
