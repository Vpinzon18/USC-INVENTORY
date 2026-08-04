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
            | IDENTIFICACIÓN DEL EQUIPO
            |--------------------------------------------------------------------------
            */

            $table->string('manufacturer',100)->nullable();

            $table->uuid('hardware_uuid')->nullable()->unique();

            $table->string('chassis_type',50)->nullable();

            $table->string('bios_version',80)->nullable();

            $table->date('bios_date')->nullable();



            /*
            |--------------------------------------------------------------------------
            | CPU
            |--------------------------------------------------------------------------
            */

            $table->smallInteger('cpu_cores')->nullable();

            $table->smallInteger('cpu_threads')->nullable();

            $table->integer('cpu_speed_mhz')->nullable();

            $table->string('cpu_architecture',20)->nullable();



            /*
            |--------------------------------------------------------------------------
            | MEMORIA RAM
            |--------------------------------------------------------------------------
            */

            $table->integer('ram_speed')->nullable();

            $table->string('ram_type',20)->nullable();

            $table->smallInteger('ram_slots')->nullable();

            $table->smallInteger('ram_modules')->nullable();



            /*
            |--------------------------------------------------------------------------
            | ALMACENAMIENTO
            |--------------------------------------------------------------------------
            */

            $table->string('storage_serial',100)->nullable();

            $table->integer('storage_capacity_gb')->nullable();

            $table->integer('storage_free_gb')->nullable();

            $table->string('storage_type',20)->nullable();

            $table->string('storage_health',20)->nullable();



            /*
            |--------------------------------------------------------------------------
            | RED
            |--------------------------------------------------------------------------
            */

            $table->string('ipv4',45)->nullable();

            $table->string('ipv6',45)->nullable();

            $table->string('gateway',45)->nullable();

            $table->string('dns_server',100)->nullable();

            $table->macAddress('ethernet_mac')->nullable();

            $table->macAddress('wifi_mac')->nullable();



            /*
            |--------------------------------------------------------------------------
            | WINDOWS
            |--------------------------------------------------------------------------
            */

            $table->string('windows_version',30)->nullable();

            $table->string('windows_build',20)->nullable();

            $table->string('windows_edition',50)->nullable();

            $table->string('license_status',40)->nullable();

            $table->boolean('secure_boot')->nullable();

            $table->string('bitlocker_status',30)->nullable();

            $table->string('tpm_version',20)->nullable();

            $table->string('antivirus',120)->nullable();

            $table->boolean('firewall_enabled')->default(false);



            /*
            |--------------------------------------------------------------------------
            | AGENTE SIGMA
            |--------------------------------------------------------------------------
            */

            $table->integer('uptime_minutes')->nullable();

            $table->timestamp('agent_last_seen')->nullable();

            $table->timestamp('last_inventory_at')->nullable();

            $table->string('hardware_hash',64)->nullable()->unique();

            $table->string('inventory_version',20)->nullable();

            $table->string('agent_version',20)->nullable();

            $table->boolean('is_online')->default(false);

            $table->string('logged_user',120)->nullable();

            $table->timestamp('last_login')->nullable();



            /*
            |--------------------------------------------------------------------------
            | MONITOR
            |--------------------------------------------------------------------------
            */

            $table->string('monitor_brand',100)->nullable();

            $table->string('monitor_model',100)->nullable();

            $table->decimal('monitor_size',4,1)->nullable();

            $table->string('monitor_resolution',20)->nullable();



            /*
            |--------------------------------------------------------------------------
            | ÍNDICES
            |--------------------------------------------------------------------------
            */

            $table->index('manufacturer');

            $table->index('model_version');

            $table->index('storage_type');

            $table->index('windows_build');

            $table->index('windows_edition');

            $table->index('hardware_uuid');

            $table->index('mac_address');

            $table->index('last_seen_at');

            $table->index('last_inventory_at');

            $table->index('agent_last_seen');

            $table->index('logged_user');

            $table->index('is_online');

            $table->index('chassis_type');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->dropUnique(['hardware_uuid']);
            $table->dropUnique(['hardware_hash']);

            $table->dropIndex(['manufacturer']);
            $table->dropIndex(['model_version']);
            $table->dropIndex(['storage_type']);
            $table->dropIndex(['windows_build']);
            $table->dropIndex(['windows_edition']);
            $table->dropIndex(['hardware_uuid']);
            $table->dropIndex(['mac_address']);
            $table->dropIndex(['last_seen_at']);
            $table->dropIndex(['last_inventory_at']);
            $table->dropIndex(['agent_last_seen']);
            $table->dropIndex(['logged_user']);
            $table->dropIndex(['is_online']);
            $table->dropIndex(['chassis_type']);

            $table->dropColumn([
                'manufacturer',
                'hardware_uuid',
                'chassis_type',
                'bios_version',
                'bios_date',

                'cpu_cores',
                'cpu_threads',
                'cpu_speed_mhz',
                'cpu_architecture',

                'ram_speed',
                'ram_type',
                'ram_slots',
                'ram_modules',

                'storage_serial',
                'storage_capacity_gb',
                'storage_free_gb',
                'storage_type',
                'storage_health',

                'ipv4',
                'ipv6',
                'gateway',
                'dns_server',
                'ethernet_mac',
                'wifi_mac',

                'windows_version',
                'windows_build',
                'windows_edition',
                'license_status',
                'secure_boot',
                'bitlocker_status',
                'tpm_version',
                'antivirus',
                'firewall_enabled',

                'uptime_minutes',
                'agent_last_seen',
                'last_inventory_at',
                'hardware_hash',
                'inventory_version',
                'agent_version',
                'is_online',
                'logged_user',
                'last_login',

                'monitor_brand',
                'monitor_model',
                'monitor_size',
                'monitor_resolution'
            ]);
        });
    }
};