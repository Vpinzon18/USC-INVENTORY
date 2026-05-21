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
    Schema::table('technical_services', function (Blueprint $table) {
        // Añadimos la llave foránea
        $table->foreignId('maintenance_schedule_id')->nullable()->constrained('maintenance_schedules')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('technical_services', function (Blueprint $table) {
        $table->dropColumn('maintenance_schedule_id');
    });
}
};
