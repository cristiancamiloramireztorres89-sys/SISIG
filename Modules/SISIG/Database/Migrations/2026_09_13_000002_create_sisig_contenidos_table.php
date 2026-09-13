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
        Schema::create('sisig_contenidos', function (Blueprint $table) {
            $table->id('id_contenido');
            $table->unsignedBigInteger('id_seccion');
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->string('url_pdf', 255)->nullable();
            $table->string('url_video', 255)->nullable();
            $table->string('url_imagen', 255)->nullable();
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
        Schema::dropIfExists('sisig_contenidos');
    }
};
