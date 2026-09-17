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
        Schema::create('sisig_modulos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('norma')->nullable();
            $table->enum('estado', ['publicado', 'revision', 'inactivo'])->default('revision');
            $table->text('descripcion')->nullable();
            $table->integer('progreso')->default(0); // Para cuando se evalúen aprendices
            $table->string('icon_class')->default('fas fa-layer-group text-blue-600');
            $table->string('icon_bg')->default('bg-blue-100');
            $table->string('border_class')->default('border-blue-100');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisig_modulos');
    }
};
