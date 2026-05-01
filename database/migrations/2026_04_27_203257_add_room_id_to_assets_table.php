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
    Schema::table('assets', function (Blueprint $table) {
        // Añadimos la relación con la tabla de salones
        // nullable() es útil si ya tienes datos en assets y aún no asignas salones
        $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropForeign(['room_id']);
        $table->dropColumn('room_id');
    });
}
};
