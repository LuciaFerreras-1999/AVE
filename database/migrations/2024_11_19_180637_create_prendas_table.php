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
        Schema::create('prendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string("slug")->unique();
            $table->text('descripcion')->nullable();
            $table->string('talla')->nullable();
            $table->string('marca')->nullable();
            $table->enum('estado', ['nuevo', 'usado'])->default('usado');
            $table->string('imagen')->nullable();
            $table->decimal('precio', 8, 2)->nullable();
            $table->boolean('publicada')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('prendas');
    }
};
