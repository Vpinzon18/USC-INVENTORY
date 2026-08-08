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
                'cpu_brand',
                'cpu_model',
                'cpu_cores',
                'cpu_threads',
                'cpu_speed_mhz',
                'cpu_architecture',
                'cpu_processor_id',
                'cpu_socket',
                'cpu_status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('cpu_brand')->nullable();
            $table->string('cpu_model')->nullable();
            $table->smallInteger('cpu_cores')->nullable();
            $table->smallInteger('cpu_threads')->nullable();
            $table->integer('cpu_speed_mhz')->nullable();
            $table->string('cpu_architecture')->nullable();
            $table->string('cpu_processor_id')->nullable();
            $table->string('cpu_socket')->nullable();
            $table->string('cpu_status')->nullable();
        });
    }
};