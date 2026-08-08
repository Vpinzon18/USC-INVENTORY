<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_gpus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('memory_mb')->nullable();
            $table->string('driver_version')->nullable();
            $table->string('processor')->nullable();
            $table->string('resolution')->nullable();
            $table->integer('refresh_rate')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_gpus');
    }
};