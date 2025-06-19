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
        Schema::create('conjuntos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('conjunto_prenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conjunto_id')->constrained();
            $table->foreignId('prenda_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conjunto_prenda');
        Schema::dropIfExists('conjuntos');
    }
};
