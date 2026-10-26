<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glpi_ticket_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 40);
            $table->string('source_type', 100);
            $table->string('source_id', 150);
            $table->string('event_key', 64)->unique();
            $table->jsonb('payload');
            $table->string('status', 20)->default('pending');
            $table->unsignedBigInteger('glpi_ticket_id')->nullable()->index();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestampTz('synced_at')->nullable();
            $table->timestampsTz();

            $table->index(['status', 'created_at']);
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glpi_ticket_syncs');
    }
};
