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
        Schema::table('estancias', function (Blueprint $table) {
            $table->string('empresa')->nullable();
        });
    }

    public function down()
    {
        Schema::table('estancias', function (Blueprint $table) {
            $table->dropColumn('empresa');
        });
    }
};
