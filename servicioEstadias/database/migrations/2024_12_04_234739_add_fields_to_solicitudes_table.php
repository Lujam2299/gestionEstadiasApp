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
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('titular_empresa')->nullable();
            $table->string('puesto_titular')->nullable();
            $table->text('objetivo')->nullable();
            $table->date('inicio_estancia')->nullable();
            $table->date('fin_estancia')->nullable();
        });
    }

    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['titular_empresa', 'puesto_titular', 'objetivo', 'inicio_estancia', 'fin_estancia']);
        });
    }
};
