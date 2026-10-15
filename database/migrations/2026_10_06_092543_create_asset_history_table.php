<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('asset_history')) {
            $required = ['id', 'asset_id', 'field_name', 'old_value', 'new_value', 'detected_at', 'source'];
            $missing = array_diff($required, Schema::getColumnListing('asset_history'));

            if ($missing !== []) {
                throw new RuntimeException(
                    'The existing asset_history table is missing required columns: '.implode(', ', $missing)
                );
            }

            return;
        }

        Schema::create('asset_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamp('detected_at')->nullable();
            $table->string('source')->nullable();
        });
    }

    public function down(): void
    {
        // The table may predate this migration; preserve it and its data on rollback.
    }
};
