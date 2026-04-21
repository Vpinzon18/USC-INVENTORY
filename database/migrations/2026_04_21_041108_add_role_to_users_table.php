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
    Schema::table('users', function (Blueprint $table) {
        /**
         * Definimos los niveles:
         * 1: SuperAdmin (Tú - Control total)
         * 2: Técnico (Puede editar equipos y ver inventario)
         * 3: Consulta (Solo puede ver, no puede editar ni borrar)
         */
        $table->integer('role')->default(3)->after('email'); 
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
};
