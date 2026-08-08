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
                'ram',
                'ram_brand',
                'ram_model',
                'ram_capacity_gb',
                'ram_speed',
                'ram_type',
                'ram_slots',
                'ram_modules',
            
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->string('ram')->nullable();
            $table->integer('ram_brand')->nullable();
            $table->integer('ram_speed')->nullable();
            $table->string('ram_type')->nullable();
            $table->integer('ram_slots')->nullable();
            $table->json('ram_capacity_gb')->nullable();
            $table->json('ram_model')->nullable();
            $table->json('ram_modules')->nullable();

        });
    }
};