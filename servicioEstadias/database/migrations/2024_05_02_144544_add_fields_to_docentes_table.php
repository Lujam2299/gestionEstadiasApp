<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            Schema::table('docentes', function (Blueprint $table) {
                //$table->string('rfc')->after('password')->nullable();
                //$table->string('nombramiento')->after('rfc')->nullable();
                //$table->string('status')->after('nombramiento')->nullable();
                //$table->string('academia')->after('status')->nullable();

            });

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            //
        });
    }
};
