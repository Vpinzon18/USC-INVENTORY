<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remote_support_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requested_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('source_asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->foreignId('target_asset_id')->constrained('assets')->restrictOnDelete();
            $table->string('request_hash', 64)->unique();
            $table->string('grant_hash', 64)->nullable()->unique();
            $table->string('source_rustdesk_id', 64)->nullable();
            $table->string('client_session_id', 20)->nullable();
            $table->string('status', 20)->default('pending');
            $table->json('scopes');
            $table->string('requested_ip', 45)->nullable();
            $table->string('source_ip', 45)->nullable();
            $table->string('target_ip', 45)->nullable();
            $table->string('close_reason', 120)->nullable();
            $table->timestampTz('expires_at');
            $table->timestampTz('authorized_at')->nullable();
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('ended_at')->nullable();
            $table->timestampsTz();

            $table->index(['target_asset_id', 'status']);
            $table->index(['requested_by_user_id', 'created_at']);
        });

        Schema::create('remote_support_session_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remote_support_session_id')
                ->constrained('remote_support_sessions')->cascadeOnDelete();
            $table->string('event', 40);
            $table->string('actor', 30)->default('system');
            $table->json('details')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index(['remote_support_session_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remote_support_session_events');
        Schema::dropIfExists('remote_support_sessions');
    }
};
