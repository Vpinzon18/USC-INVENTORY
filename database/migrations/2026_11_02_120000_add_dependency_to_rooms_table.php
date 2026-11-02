<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('dependency_id')
                ->nullable()
                ->after('building_id')
                ->constrained('dependencies')
                ->nullOnDelete();
        });

        // Preserve unambiguous legacy links. Rooms connected to custodians from
        // more than one dependency stay null so an administrator can resolve them.
        $legacyLinks = DB::table('custodian_room')
            ->join('custodians', 'custodians.id', '=', 'custodian_room.custodian_id')
            ->whereNotNull('custodians.dependency_id')
            ->select('custodian_room.room_id', 'custodians.dependency_id')
            ->distinct()
            ->get()
            ->concat(DB::table('assignments')
                ->join('custodians', 'custodians.id', '=', 'assignments.custodian_id')
                ->where('assignments.status', 'active')
                ->whereNotNull('assignments.room_id')
                ->whereNotNull('custodians.dependency_id')
                ->select('assignments.room_id', 'custodians.dependency_id')
                ->distinct()
                ->get())
            ->unique(fn ($link) => $link->room_id . ':' . $link->dependency_id)
            ->groupBy('room_id');

        foreach ($legacyLinks as $roomId => $links) {
            $dependencyIds = $links->pluck('dependency_id')->unique();

            if ($dependencyIds->count() === 1) {
                DB::table('rooms')
                    ->where('id', $roomId)
                    ->update(['dependency_id' => $dependencyIds->first()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dependency_id');
        });
    }
};
