<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->uuid('agent_uuid')->nullable()->unique();
            $table->dropUnique(['serial_number']);
            $table->string('serial_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        $hasMissingSerial = DB::table('assets')->whereNull('serial_number')->exists();
        $hasDuplicateSerial = DB::table('assets')
            ->select('serial_number')
            ->whereNotNull('serial_number')
            ->groupBy('serial_number')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasMissingSerial || $hasDuplicateSerial) {
            throw new RuntimeException(
                'Cannot restore unique, required serial numbers while assets have missing or duplicate serials.'
            );
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->dropUnique(['agent_uuid']);
            $table->dropColumn('agent_uuid');
            $table->string('serial_number')->nullable(false)->change();
            $table->unique('serial_number');
        });
    }
};
