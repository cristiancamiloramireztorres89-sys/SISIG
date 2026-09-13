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
        Schema::create('sisig_opciones', function (Blueprint $table) {
            $table->id('id_opcion');
            $table->unsignedBigInteger('id_pregunta');
            $table->string('opcion', 255);
            $table->boolean('es_correcta')->default(false);
            $table->timestamps();

            $table->foreign('id_pregunta')
                  ->references('id_pregunta')
                  ->on('sisig_preguntas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisig_opciones');
    }
};
