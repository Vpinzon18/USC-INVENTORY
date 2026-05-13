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
    Schema::table('assets', function (Blueprint $table) {
        // Agregamos la columna y la conectamos con la tabla de responsables
        $table->foreignId('custodian_id')->nullable()->constrained('custodians')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('assets', function (Blueprint $table) {
        $table->dropForeign(['custodian_id']);
        $table->dropColumn('custodian_id');
    });
}
};
