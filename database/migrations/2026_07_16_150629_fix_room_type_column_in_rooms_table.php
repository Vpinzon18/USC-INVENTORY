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
            // 1. Eliminamos la columna vieja de texto
            if (Schema::hasColumn('rooms', 'type')) {
                $table->dropColumn('type');
            }
            
            // 2. Agregamos la llave foránea correcta
            if (!Schema::hasColumn('rooms', 'room_type_id')) {
                $table->foreignId('room_type_id')
                      ->nullable()
                      ->after('floor') // o 'name'
                      ->constrained('room_types')
                      ->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');
            $table->string('type')->default('Oficina');
        });
    }
};