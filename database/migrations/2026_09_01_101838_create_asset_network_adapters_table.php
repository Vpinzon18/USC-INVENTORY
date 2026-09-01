<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_network_adapters', function (Blueprint $table) {

            $table->id();

            $table->foreignId('asset_network_id')
                ->constrained('asset_networks')
                ->cascadeOnDelete();

            $table->string('name')->nullable();

            $table->string('description')->nullable();

            $table->macAddress('mac_address')->nullable();

            $table->string('type')->nullable();

            $table->string('ipv4')->nullable();

            $table->string('ipv6')->nullable();

            $table->boolean('connected')
                ->default(false);

            $table->boolean('is_primary')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_network_adapters');
    }
};