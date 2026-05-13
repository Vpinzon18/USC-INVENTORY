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
        // Campos para periféricos y seguridad física
        $table->string('monitor_asset')->nullable()->after('hostname'); // Activo Monitor
        $table->string('monitor_serial')->nullable()->after('monitor_asset');
        $table->string('keyboard_serial')->nullable()->after('monitor_serial');
        $table->string('mouse_serial')->nullable()->after('keyboard_serial');
        $table->string('security_guaya')->nullable()->after('mouse_serial'); // Guaya
    });
}

public function down(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropColumn(['monitor_asset', 'monitor_serial', 'keyboard_serial', 'mouse_serial', 'security_guaya']);
    });
}
};
