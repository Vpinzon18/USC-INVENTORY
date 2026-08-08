<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_ram_modules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('asset_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('slot')->nullable();

            $table->string('manufacturer')->nullable();

            $table->string('model')->nullable();

            $table->string('serial_number')->nullable();

            $table->integer('capacity_gb')->nullable();

            $table->integer('speed_mhz')->nullable();

            $table->string('memory_type')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_ram_modules');
    }
};