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
        Schema::create('sisig_preguntas', function (Blueprint $table) {
            $table->id('id_pregunta');
            $table->unsignedBigInteger('id_quiz');
            $table->text('pregunta');
            $table->string('tipo', 30)->default('opcion_multiple'); // opcion_multiple, verdadero_falso, abierta
            $table->decimal('puntaje', 5, 2)->default(1.00);
            $table->timestamps();

            $table->foreign('id_quiz')
                  ->references('id_quiz')
                  ->on('sisig_quices')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisig_preguntas');
    }
};
