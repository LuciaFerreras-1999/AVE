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
        Schema::create('valoraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('valorador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('valorado_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('puntuacion');
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->unique(['valorador_id', 'valorado_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoraciones');
    }
};
