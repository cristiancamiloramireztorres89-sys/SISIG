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
        // 1. Agregar campo phone a la tabla people según el diccionario de datos
        if (Schema::hasTable('people')) {
            Schema::table('people', function (Blueprint $table) {
                if (!Schema::hasColumn('people', 'phone')) {
                    $table->string('phone', 20)->nullable()->after('personal_email');
                }
            });
        }

        // 2. Agregar campo program a la tabla apprentices para datos de formación del aprendiz
        if (Schema::hasTable('apprentices')) {
            Schema::table('apprentices', function (Blueprint $table) {
                if (!Schema::hasColumn('apprentices', 'program')) {
                    $table->string('program', 150)->nullable()->default('ADSO - Análisis y Desarrollo de Software')->after('course_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('people')) {
            Schema::table('people', function (Blueprint $table) {
                if (Schema::hasColumn('people', 'phone')) {
                    $table->dropColumn('phone');
                }
            });
        }

        if (Schema::hasTable('apprentices')) {
            Schema::table('apprentices', function (Blueprint $table) {
                if (Schema::hasColumn('apprentices', 'program')) {
                    $table->dropColumn('program');
                }
            });
        }
    }
};
