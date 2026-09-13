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
        Schema::create('sisig_quices', function (Blueprint $table) {
            $table->id('id_quiz');
            $table->unsignedBigInteger('id_seccion');
            $table->string('titulo', 100);
            $table->decimal('puntaje_minimo', 5, 2)->default(70.00);
            $table->integer('numero_preguntas')->default(5);
            $table->integer('numero_intentos')->nullable()->default(3);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_seccion')
                  ->references('id_seccion')
                  ->on('sisig_secciones')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisig_quices');
    }
};
