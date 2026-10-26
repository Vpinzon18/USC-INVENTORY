<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sigma_agent_enrollment_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_id')->unique();
            $table->jsonb('device');
            $table->text('public_key');
            $table->string('public_key_fingerprint', 64);
            $table->string('request_ip', 45)->nullable();
            $table->string('status', 16)->default('pending')->index();
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('encrypted_credential')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sigma_agent_enrollment_requests');
    }
};
