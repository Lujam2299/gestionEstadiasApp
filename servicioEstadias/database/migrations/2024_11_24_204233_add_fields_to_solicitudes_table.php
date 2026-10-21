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
            $table->string('proyecto')->nullable();
            $table->string('plan_estudios')->nullable();
            $table->string('giro_empresa')->nullable();
            $table->string('area_complementacion')->nullable();
        });
    }

    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['proyecto', 'plan_estudios', 'giro_empresa', 'area_complementacion']);
        });
    }
};
