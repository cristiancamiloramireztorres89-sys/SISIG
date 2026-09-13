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
        // 1. Tabla 'people' (Personas / Aprendices / Instructores)
        if (!Schema::hasTable('people')) {
            Schema::create('people', function (Blueprint $table) {
                $table->id();
                $table->string('document_type', 50)->default('Cédula de ciudadanía');
                $table->unsignedBigInteger('document_number')->unique();
                $table->string('first_name', 100);
                $table->string('first_last_name', 100);
                $table->string('second_last_name', 100)->nullable();
                $table->string('personal_email', 150)->nullable();
                $table->string('sena_email', 150)->nullable();
                $table->string('avatar', 255)->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 2. Adecuar columnas de la tabla 'users' para vincular con 'people' y permitir login por nickname
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nickname')) {
                $table->string('nickname', 100)->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'person_id')) {
                $table->foreignId('person_id')->nullable()->after('nickname')->constrained('people')->onDelete('cascade');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // 3. Tabla 'roles' (Roles de usuario del ERP y SISIG)
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->string('description', 255)->nullable();
                $table->string('description_english', 255)->nullable();
                $table->enum('full_access', ['Si', 'No'])->default('No');
                if (Schema::hasTable('apps')) {
                    $table->foreignId('app_id')->nullable()->constrained('apps')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('app_id')->nullable();
                }
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 4. Tabla pivote 'role_user' (Asignación de roles a usuarios)
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 5. Tabla 'apprentices' (Ficha / Datos formativos del aprendiz en SENA Empresa)
        if (!Schema::hasTable('apprentices')) {
            Schema::create('apprentices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
                $table->unsignedBigInteger('course_id')->nullable();
                $table->string('apprentice_status', 50)->default('En formación');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('apprentices');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'person_id')) {
                    $table->dropForeign(['person_id']);
                    $table->dropColumn('person_id');
                }
                if (Schema::hasColumn('users', 'nickname')) {
                    $table->dropColumn('nickname');
                }
                if (Schema::hasColumn('users', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }

        Schema::dropIfExists('people');
        Schema::enableForeignKeyConstraints();
    }
};
