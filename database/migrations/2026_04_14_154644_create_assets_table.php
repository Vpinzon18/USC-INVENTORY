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
    Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->string('serial_number')->unique();
        $table->string('hostname');
        $table->string('ip_address')->nullable();
        $table->foreignId('sede_id')->nullable(); 
        $table->timestamp('last_seen_at')->nullable();
        $table->timestamps(); 
        $table->string('internal_code')->nullable(); // Agrégalo aquí
        
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
