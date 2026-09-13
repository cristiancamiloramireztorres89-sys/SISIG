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
        Schema::create('infraccionesquiz', function (Blueprint $table) {
            $table->id('id_infraccion');
            $table->unsignedBigInteger('id_resultado');
            $table->string('tipo_infraccion', 50); // cambio_pestana, salida_pantalla_completa, tecla_bloqueada
            $table->timestamp('marca_tiempo')->nullable();
            $table->string('navegador_detalles', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_resultado')
                  ->references('id_resultado')
                  ->on('sisig_resultados')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infraccionesquiz');
    }
};
