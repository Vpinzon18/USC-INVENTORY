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
        Schema::create('asset_monitors', function (Blueprint $table) {

    $table->id();

    $table->foreignId('asset_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('brand')->nullable();

    $table->string('model')->nullable();

    $table->string('serial_number')->nullable();

    $table->string('manufacturer_code')->nullable();

    $table->integer('year')->nullable();

    $table->decimal('size',5,2)->nullable();

    $table->string('resolution')->nullable();

    $table->integer('refresh_rate')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_monitors');
    }
};
