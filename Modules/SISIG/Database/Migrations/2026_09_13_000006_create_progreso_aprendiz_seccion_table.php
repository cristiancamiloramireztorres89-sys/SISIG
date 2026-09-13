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
        Schema::create('progreso_aprendiz_seccion', function (Blueprint $table) {
            $table->id('id_progreso');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_seccion');
            $table->string('estado', 20)->default('No iniciado'); // No iniciado, En progreso, Completado
            $table->decimal('porcentaje_visto', 5, 2)->default(0.00);
            $table->timestamp('ultima_visualizacion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('progreso_aprendiz_seccion');
    }
};
