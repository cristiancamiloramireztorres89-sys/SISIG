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
        Schema::create('actividadreciente', function (Blueprint $table) {
            $table->id('id_actividad');
            $table->unsignedBigInteger('user_id');
            $table->string('tipo_evento', 50); // inicio_sesion, seccion_vista, quiz_iniciado, quiz_finalizado, etc.
            $table->text('mensaje');
            $table->timestamp('fecha_hora')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividadreciente');
    }
};
