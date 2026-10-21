<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurpToDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::table('docentes', function (Blueprint $table) {
        //$table->string('curp')->nullable()->after('rfc');
        //});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::table('docentes', function (Blueprint $table) {
        //  $table->dropColumn('curp');
        //});
    }
}
