<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'gpu_brand',
                'gpu_model',
                'gpu_memory_mb',
                'gpu_driver_version',
                'gpu_processor',
                'gpu_resolution',
                'gpu_refresh_rate',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('gpu_brand')->nullable();
            $table->string('gpu_model')->nullable();
            $table->integer('gpu_memory_mb')->nullable();
            $table->string('gpu_driver_version')->nullable();
            $table->string('gpu_processor')->nullable();
            $table->string('gpu_resolution')->nullable();
            $table->integer('gpu_refresh_rate')->nullable();
        });
    }
};