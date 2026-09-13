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
        Schema::create('sisig_resultados', function (Blueprint $table) {
            $table->id('id_resultado');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_quiz');
            $table->integer('intento')->default(1);
            $table->decimal('puntaje', 5, 2)->default(0.00);
            $table->boolean('aprobado')->default(false);
            $table->boolean('fue_anulado')->default(false);
            $table->string('motivo_anulacion', 255)->nullable();
            $table->timestamp('fecha_presentacion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('sisig_resultados');
    }
};
