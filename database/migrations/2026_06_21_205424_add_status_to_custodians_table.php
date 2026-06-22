<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custodians', function (Blueprint $table) {
            // Agregamos la columna status, por defecto todos nacen activos
            $table->string('status', 20)->default('active')->after('document_number');
        });
    }

    public function down(): void
    {
        Schema::table('custodians', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};