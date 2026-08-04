<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_storage_devices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('asset_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('brand')->nullable();

            $table->string('model')->nullable();

            $table->string('serial_number')->nullable();

            $table->integer('capacity_gb')->nullable();

            $table->integer('free_gb')->nullable();

            $table->string('type')->nullable();

            $table->string('health')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_storage_devices');
    }
};