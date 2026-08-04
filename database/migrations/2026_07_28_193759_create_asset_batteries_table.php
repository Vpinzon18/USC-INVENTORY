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
                'battery_health',
                'battery_cycle_count',
                'battery_design_capacity',
                'battery_full_charge_capacity',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->string('battery_health')->nullable();

            $table->integer('battery_cycle_count')->nullable();

            $table->integer('battery_design_capacity')->nullable();

            $table->integer('battery_full_charge_capacity')->nullable();

        });
    }
};