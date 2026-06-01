<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    // Cambiamos "movements" por "assignments"
    Schema::table('assignments', function (Blueprint $table) {
        $table->string('movement_type')->nullable(); 
        $table->string('headquarters')->nullable();  
        $table->string('acta_number')->nullable();   
        $table->unsignedBigInteger('user_id')->nullable(); 
        
        $table->foreign('user_id')->references('id')->on('users');
    });
}

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['movement_type', 'headquarters', 'acta_number', 'user_id']);
        });
    }
};