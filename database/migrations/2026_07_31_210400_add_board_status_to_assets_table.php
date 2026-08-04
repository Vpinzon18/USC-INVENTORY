<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->string('board_status')
                  ->nullable()
                  ->after('board_version');

        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->dropColumn('board_status');

        });
    }
};