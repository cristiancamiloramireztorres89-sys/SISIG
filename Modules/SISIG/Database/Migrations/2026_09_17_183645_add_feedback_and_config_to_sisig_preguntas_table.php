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
        Schema::table('sisig_preguntas', function (Blueprint $table) {
            $table->text('retroalimentacion')->nullable()->after('puntaje');
            $table->json('configuracion_json')->nullable()->after('retroalimentacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sisig_preguntas', function (Blueprint $table) {
            $table->dropColumn(['retroalimentacion', 'configuracion_json']);
        });
    }
};
