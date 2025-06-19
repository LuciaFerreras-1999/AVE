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
        Schema::table('mensaje_privados', function (Blueprint $table) {
            $table->boolean('leido')->default(false);
        });
    }

    public function down()
    {
        Schema::table('mensaje_privados', function (Blueprint $table) {
            $table->dropColumn('leido');
        });
    }
};
