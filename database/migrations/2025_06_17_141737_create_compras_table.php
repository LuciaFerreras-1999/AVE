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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('prenda_id')->nullable();

            $table->decimal('precio', 8, 2)->nullable();
            $table->timestamp('fecha_compra')->useCurrent();
            $table->timestamps();
        });

        Schema::table('compras', function (Blueprint $table) {
            $table->foreign('prenda_id')->references('id')->on('prendas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
