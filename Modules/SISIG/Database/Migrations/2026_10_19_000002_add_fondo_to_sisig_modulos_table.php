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
        Schema::table('sisig_modulos', function (Blueprint $table) {
            if (!Schema::hasColumn('sisig_modulos', 'fondo_imagen')) {
                $table->string('fondo_imagen')->nullable()->after('contenido_html');
            }
            if (!Schema::hasColumn('sisig_modulos', 'fondo_opacidad')) {
                $table->integer('fondo_opacidad')->default(20)->after('fondo_imagen');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sisig_modulos', function (Blueprint $table) {
            if (Schema::hasColumn('sisig_modulos', 'fondo_opacidad')) {
                $table->dropColumn('fondo_opacidad');
            }
            if (Schema::hasColumn('sisig_modulos', 'fondo_imagen')) {
                $table->dropColumn('fondo_imagen');
            }
        });
    }
};
