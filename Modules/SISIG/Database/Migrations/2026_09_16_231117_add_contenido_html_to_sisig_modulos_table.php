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
            $table->longText('contenido_html')->nullable()->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sisig_modulos', function (Blueprint $table) {
            $table->dropColumn('contenido_html');
        });
    }
};
