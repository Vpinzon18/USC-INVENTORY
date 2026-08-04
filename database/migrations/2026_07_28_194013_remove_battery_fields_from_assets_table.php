<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_batteries', function (Blueprint $table) {

            $table->dropColumn('health');

            $table->unsignedTinyInteger('health_percent')
                  ->default(100)
                  ->after('serial_number');

        });
    }

    public function down(): void
    {
        Schema::table('asset_batteries', function (Blueprint $table) {

            $table->dropColumn('health_percent');

            $table->string('health')
                  ->nullable()
                  ->after('serial_number');

        });
    }
};