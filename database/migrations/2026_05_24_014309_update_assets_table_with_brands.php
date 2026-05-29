<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('assets', function (Blueprint $table) {
        // CPU
        $table->string('cpu_brand')->nullable();
        $table->string('cpu_model')->nullable();
        // GPU
        $table->string('gpu_brand')->nullable();
        $table->string('gpu_model')->nullable();
        // WIFI
        $table->string('wifi_brand')->nullable();
        $table->string('wifi_model')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
