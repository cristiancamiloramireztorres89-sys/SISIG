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
            if (!Schema::hasColumn('sisig_modulos', 'imagen')) {
                $table->string('imagen')->nullable()->after('descripcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sisig_modulos', function (Blueprint $table) {
            if (Schema::hasColumn('sisig_modulos', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }
};
