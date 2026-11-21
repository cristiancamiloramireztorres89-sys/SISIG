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
        if (Schema::hasTable('sisig_modulos')) {
            Schema::table('sisig_modulos', function (Blueprint $table) {
                if (!Schema::hasColumn('sisig_modulos', 'canvas_state')) {
                    $table->longText('canvas_state')->nullable()->after('contenido_html');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sisig_modulos')) {
            Schema::table('sisig_modulos', function (Blueprint $table) {
                if (Schema::hasColumn('sisig_modulos', 'canvas_state')) {
                    $table->dropColumn('canvas_state');
                }
            });
        }
    }
};

