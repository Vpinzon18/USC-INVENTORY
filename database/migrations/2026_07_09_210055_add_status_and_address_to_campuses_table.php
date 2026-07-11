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
        Schema::table('campuses', function (Blueprint $table) {
            // Añadimos el campo de dirección (puede ser nulo si no lo llenan)
            $table->string('address')->nullable()->after('city');
            
            // Añadimos el campo de estado con un valor por defecto activo
            $table->string('status')->default('Activa')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->dropColumn(['address', 'status']);
        });
    }
};