<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('assets', function (Blueprint $table) {
        $table->string('mac_address')->nullable();
        $table->string('wifi_card')->nullable();
        $table->string('graphics_card')->nullable();
        $table->string('os_version')->nullable();
        $table->string('domain_name')->nullable();
    });
}

public function down()
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropColumn(['mac_address', 'wifi_card', 'graphics_card', 'os_version', 'domain_name']);
    });
}
};
