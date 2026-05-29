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
            // Añadimos las columnas que te faltaban
            $table->string('board_brand')->nullable();
            $table->string('board_model')->nullable();
            $table->string('storage_brand')->nullable();
            $table->string('storage_model')->nullable();
        });
    }

    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['board_brand', 'board_model', 'storage_brand', 'storage_model']);
        });
    }
};
