<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_networks', function (Blueprint $table) {

            $table->id();

            $table->foreignId('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            $table->string('gateway')->nullable();

            $table->string('dns_server')->nullable();

            $table->boolean('dhcp_enabled')
                ->default(false);

            $table->boolean('connected')
                ->default(false);

            $table->timestamps();

            $table->unique('asset_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_networks');
    }
};