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
    // Tabla de Sedes (Ej: Palmira, Cali)
    Schema::create('campuses', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    // Tabla de Bloques (Ej: Bloque Administrativo, Bloque C)
    Schema::create('buildings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('campus_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->timestamps();
    });

    // Tabla de Salones/Oficinas
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('building_id')->constrained()->onDelete('cascade');
        $table->string('name'); // Ejemplo: 'Laboratorio 404'
        $table->integer('floor'); // Piso
        $table->timestamps();
    });
}
};
