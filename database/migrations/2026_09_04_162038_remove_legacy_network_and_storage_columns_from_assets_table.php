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
                // Red
                'ip_address',
                'mac_address',
                'ipv4',
                'ipv6',
                'gateway',
                'dns_server',
                'ethernet_mac',
                'wifi_mac',

                // Storage
                'storage_brand',
                'storage_model',
                'storage_serial',
                'storage_capacity_gb',
                'storage_free_gb',
                'storage_type',
                'storage_health',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Red
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ipv4')->nullable();
            $table->string('ipv6')->nullable();
            $table->string('gateway')->nullable();
            $table->string('dns_server')->nullable();
            $table->macAddress('ethernet_mac')->nullable();
            $table->macAddress('wifi_mac')->nullable();

            // Storage
            $table->string('storage_brand')->nullable();
            $table->string('storage_model')->nullable();
            $table->string('storage_serial')->nullable();
            $table->integer('storage_capacity_gb')->nullable();
            $table->integer('storage_free_gb')->nullable();
            $table->string('storage_type')->nullable();
            $table->string('storage_health')->nullable();
        });
    }
};