<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('asset_ram_modules')) {
            throw new RuntimeException('The asset_ram_modules table must exist before adding its bank column.');
        }

        if (! Schema::hasColumn('asset_ram_modules', 'bank')) {
            Schema::table('asset_ram_modules', function (Blueprint $table) {
                $table->string('bank')->nullable()->after('serial_number');
            });
        }
    }

    public function down(): void
    {
        // This column may have been created by an earlier migration; keep it to avoid data loss.
    }
};
