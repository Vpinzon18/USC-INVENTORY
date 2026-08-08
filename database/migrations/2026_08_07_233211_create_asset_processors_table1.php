<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_processors', function (Blueprint $table) {

            $table->id();

            $table->foreignId('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            $table->string('brand')->nullable();

            $table->string('model')->nullable();

            $table->string('processor_id')->nullable();

            $table->string('socket')->nullable();

            $table->string('status')->nullable();

            $table->integer('cores')->nullable();

            $table->integer('threads')->nullable();

            $table->integer('speed_mhz')->nullable();

            $table->string('architecture')->nullable();

            $table->string('family')->nullable();

            $table->string('generation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_processors');
    }
};