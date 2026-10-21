<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodoDuracionToSolicitudesTable extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('solicitudes', 'periodo_duracion')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                $table->string('periodo_duracion')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn('periodo_duracion');
        });
    }
}
