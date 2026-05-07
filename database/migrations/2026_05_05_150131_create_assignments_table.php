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
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asset_id')->constrained()->onDelete('cascade');
        $table->foreignId('custodian_id')->constrained()->onDelete('cascade');
        $table->foreignId('room_id')->constrained(); // Ubicación en ese momento
        
        $table->dateTime('started_at'); // Fecha de entrega
        $table->dateTime('ended_at')->nullable(); // Se llena cuando el director entrega el equipo
        
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->text('observations')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
