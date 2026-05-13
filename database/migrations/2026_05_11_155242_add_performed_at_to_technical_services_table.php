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
    Schema::table('technical_services', function (Blueprint $table) {
        // Agregamos el campo de fecha justo después del ID del técnico
        $table->date('performed_at')->nullable()->after('user_id');
    });
}

public function down(): void
{
    Schema::table('technical_services', function (Blueprint $table) {
        $table->dropColumn('performed_at');
    });
}
};
