<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_batteries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();

            $table->string('health')->nullable();

            $table->integer('cycle_count')->nullable();

            $table->integer('design_capacity')->nullable();
            $table->integer('full_charge_capacity')->nullable();
            $table->integer('remaining_capacity')->nullable();

            $table->integer('charge_percent')->nullable();

            $table->boolean('is_charging')
                ->default(false);

            $table->integer('estimated_runtime_minutes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_batteries');
    }
};