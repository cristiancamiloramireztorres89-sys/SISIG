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
        Schema::table('sisig_quices', function (Blueprint $table) {
            // Eliminar la foránea vieja
            $table->dropForeign(['id_seccion']);
            $table->dropColumn('id_seccion');

            // Añadir campos nuevos
            $table->unsignedBigInteger('modulo_id')->nullable()->after('id_quiz');
            $table->integer('tiempo_minutos')->default(0)->after('numero_intentos');
            $table->date('fecha_limite')->nullable()->after('tiempo_minutos');

            // Nueva foránea
            $table->foreign('modulo_id')->references('id')->on('sisig_modulos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sisig_quices', function (Blueprint $table) {
            $table->dropForeign(['modulo_id']);
            $table->dropColumn(['modulo_id', 'tiempo_minutos', 'fecha_limite']);

            $table->unsignedBigInteger('id_seccion')->nullable();
            $table->foreign('id_seccion')->references('id_seccion')->on('sisig_secciones')->onDelete('cascade');
        });
    }
};
