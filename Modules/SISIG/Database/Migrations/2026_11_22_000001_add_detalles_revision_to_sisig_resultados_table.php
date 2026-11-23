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
        if (Schema::hasTable('sisig_resultados')) {
            Schema::table('sisig_resultados', function (Blueprint $table) {
                if (!Schema::hasColumn('sisig_resultados', 'detalles_revision')) {
                    $table->json('detalles_revision')->nullable()->after('infracciones_detectadas');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sisig_resultados')) {
            Schema::table('sisig_resultados', function (Blueprint $table) {
                if (Schema::hasColumn('sisig_resultados', 'detalles_revision')) {
                    $table->dropColumn('detalles_revision');
                }
            });
        }
    }
};
