<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {

    $table->dropColumn([

        'monitor_brand',

        'monitor_model',

        'monitor_asset',

        'monitor_serial',

        'monitor_size',

        'monitor_resolution',

        'monitor_refresh_rate'

    ]);

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
        });
    }
};
