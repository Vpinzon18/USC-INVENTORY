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
        $table->dropColumn(['dependency', 'job_title']);
    });
}

public function down()
{
    Schema::table('custodians', function (Blueprint $table) {
        $table->string('dependency')->nullable();
        $table->string('job_title')->nullable();
    });
}
};
