<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | CPU
            |--------------------------------------------------------------------------
            */

            $table->string('cpu_processor_id')->nullable()->after('cpu_architecture');
            $table->string('cpu_socket')->nullable()->after('cpu_processor_id');
            $table->string('cpu_status')->nullable()->after('cpu_socket');

            /*
            |--------------------------------------------------------------------------
            | GPU
            |--------------------------------------------------------------------------
            */

            $table->integer('gpu_memory_mb')->nullable()->after('gpu_model');
            $table->string('gpu_driver_version')->nullable()->after('gpu_memory_mb');
            $table->string('gpu_processor')->nullable()->after('gpu_driver_version');
            $table->string('gpu_resolution')->nullable()->after('gpu_processor');
            $table->integer('gpu_refresh_rate')->nullable()->after('gpu_resolution');

            /*
            |--------------------------------------------------------------------------
            | Motherboard
            |--------------------------------------------------------------------------
            */

            $table->string('board_serial')->nullable()->after('board_model');
            $table->string('board_version')->nullable()->after('board_serial');

            /*
            |--------------------------------------------------------------------------
            | BIOS
            |--------------------------------------------------------------------------
            */

            $table->string('bios_manufacturer')->nullable()->after('bios_date');
            $table->string('bios_serial')->nullable()->after('bios_manufacturer');
            $table->timestamp('bios_release_date')->nullable()->after('bios_serial');

            /*
            |--------------------------------------------------------------------------
            | Monitor
            |--------------------------------------------------------------------------
            */

            $table->integer('monitor_refresh_rate')->nullable()->after('monitor_resolution');

            /*
            |--------------------------------------------------------------------------
            | Batería
            |--------------------------------------------------------------------------
            */

            $table->string('battery_health')->nullable()->after('monitor_refresh_rate');
            $table->integer('battery_cycle_count')->nullable()->after('battery_health');
            $table->integer('battery_design_capacity')->nullable()->after('battery_cycle_count');
            $table->integer('battery_full_charge_capacity')->nullable()->after('battery_design_capacity');

            /*
            |--------------------------------------------------------------------------
            | Agente
            |--------------------------------------------------------------------------
            */

            $table->string('agent_version')->nullable()->change();

            $table->timestamp('agent_last_seen')->nullable()->change();

            $table->timestamp('last_inventory_at')->nullable()->change();

            $table->string('inventory_version')->nullable()->change();

            $table->string('hardware_hash')->nullable()->change();

            $table->boolean('is_agent_managed')->default(false)->change();

            $table->boolean('is_online')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->dropColumn([
                'cpu_processor_id',
                'cpu_socket',
                'cpu_status',

                'gpu_memory_mb',
                'gpu_driver_version',
                'gpu_processor',
                'gpu_resolution',
                'gpu_refresh_rate',

                'board_serial',
                'board_version',

                'bios_manufacturer',
                'bios_serial',
                'bios_release_date',

                'monitor_refresh_rate',

                'battery_health',
                'battery_cycle_count',
                'battery_design_capacity',
                'battery_full_charge_capacity',
            ]);
        });
    }
};