<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sigma_agent_enrollment_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code_hash', 64)->unique();
            $table->foreignId('created_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->timestamp('expires_at')->index();
            $table->timestamp('consumed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sigma_agent_enrollment_codes');
    }
};
