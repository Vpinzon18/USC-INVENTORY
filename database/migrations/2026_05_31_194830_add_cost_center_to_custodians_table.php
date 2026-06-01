<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('custodians', function (Blueprint $table) {
        $table->string('cost_center', 50)->nullable()->after('full_name');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custodians', function (Blueprint $table) {
            //
        });
    }
};
