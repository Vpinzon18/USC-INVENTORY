<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_network_adapters', function (Blueprint $table) {

            $table->boolean('is_active')
                ->default(true)
                ->after('is_primary');

            $table->timestamp('last_seen_at')
                ->nullable()
                ->after('is_active');

            $table->unique(
                ['asset_network_id', 'mac_address'],
                'asset_network_adapter_mac_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('asset_network_adapters', function (Blueprint $table) {

            $table->dropUnique(
                'asset_network_adapter_mac_unique'
            );

            $table->dropColumn([
                'is_active',
                'last_seen_at',
            ]);
        });
    }
};