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
        // 1. Mejorar tabla de infracciones del quiz para auditoría completa
        if (Schema::hasTable('infraccionesquiz')) {
            Schema::table('infraccionesquiz', function (Blueprint $table) {
                if (!Schema::hasColumn('infraccionesquiz', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id_resultado');
                }
                if (!Schema::hasColumn('infraccionesquiz', 'id_quiz')) {
                    $table->unsignedBigInteger('id_quiz')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('infraccionesquiz', 'tipo_evento')) {
                    $table->string('tipo_evento', 100)->nullable()->after('tipo_infraccion');
                }
                if (!Schema::hasColumn('infraccionesquiz', 'detalle')) {
                    $table->text('detalle')->nullable()->after('tipo_evento');
                }
                if (!Schema::hasColumn('infraccionesquiz', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable()->after('navegador_detalles');
                }
            });
        }

        // 2. Extender tabla de resultados con métricas de tiempo e incidentes
        if (Schema::hasTable('sisig_resultados')) {
            Schema::table('sisig_resultados', function (Blueprint $table) {
                if (!Schema::hasColumn('sisig_resultados', 'tiempo_segundos_empleado')) {
                    $table->integer('tiempo_segundos_empleado')->default(0)->after('fecha_presentacion');
                }
                if (!Schema::hasColumn('sisig_resultados', 'infracciones_detectadas')) {
                    $table->integer('infracciones_detectadas')->default(0)->after('tiempo_segundos_empleado');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('infraccionesquiz')) {
            Schema::table('infraccionesquiz', function (Blueprint $table) {
                $columns = ['user_id', 'id_quiz', 'tipo_evento', 'detalle', 'ip_address'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('infraccionesquiz', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('sisig_resultados')) {
            Schema::table('sisig_resultados', function (Blueprint $table) {
                $columns = ['tiempo_segundos_empleado', 'infracciones_detectadas'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('sisig_resultados', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
