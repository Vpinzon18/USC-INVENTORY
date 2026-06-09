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
        $table->foreignId('dependency_id')->nullable()->constrained('dependencies');
        $table->foreignId('job_title_id')->nullable()->constrained('job_titles');
    });
}

public function down()
{
    Schema::table('custodians', function (Blueprint $table) {
        $table->dropForeign(['dependency_id']);
        $table->dropForeign(['job_title_id']);
        $table->dropColumn(['dependency_id', 'job_title_id']);
    });
}
};
