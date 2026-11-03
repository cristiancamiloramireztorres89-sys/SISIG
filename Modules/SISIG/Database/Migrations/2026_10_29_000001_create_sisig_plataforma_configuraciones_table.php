<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sisig_plataforma_configuraciones', function (Blueprint $table) {
            $table->id();
            
            // 1. Identidad Visual
            $table->string('nombre_plataforma', 150)->default('SISIG - SENA Empresa');
            $table->string('logo_path', 255)->nullable()->default('modules/sisig/images/logo.png');
            
            // 2. Textos Legales e Institucionales (HTML Enriquecido)
            $table->longText('terminos_condiciones')->nullable();
            $table->longText('politicas_privacidad')->nullable();
            
            // 3. Restricciones Operativas para editor_sisig
            $table->boolean('editor_bloquear_edicion_critica')->default(true);
            $table->boolean('editor_limitar_creacion_modulos')->default(false);
            $table->boolean('editor_restringir_eliminacion_quices')->default(true);
            $table->boolean('editor_requerir_aprobacion')->default(false);
            
            // Auditoría del último administrador en modificar
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sisig_plataforma_configuraciones');
    }
};
