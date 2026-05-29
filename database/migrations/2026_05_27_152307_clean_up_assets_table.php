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
        // Eliminamos las columnas genéricas redundantes
        $table->dropColumn(['cpu', 'storage', 'wifi_card', 'graphics_card']);
        
        // Nota: Mantenemos 'ram' porque es un dato simple, pero podrías 
        // renombrarlo a 'ram_capacity' si quieres ser estricto.
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
