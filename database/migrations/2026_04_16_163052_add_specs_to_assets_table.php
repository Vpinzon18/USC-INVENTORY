<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('assets', function (Blueprint $table) {
        $table->string('cpu')->nullable();
        $table->string('ram')->nullable();
        $table->string('storage')->nullable();
    });
}
};
