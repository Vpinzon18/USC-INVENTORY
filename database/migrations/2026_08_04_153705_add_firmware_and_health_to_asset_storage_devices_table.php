<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
{
    Schema::table('asset_storage_devices', function (Blueprint $table) {
        $table->string('firmware')->nullable()->after('serial_number');
        $table->string('health')->nullable()->after('type');
    });
}

public function down(): void
{
    Schema::table('asset_storage_devices', function (Blueprint $table) {
        $table->dropColumn(['firmware', 'health']);
    });
}
};
