<?php

namespace Modules\SISIG\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\SICA\Entities\Person;
use Modules\SICA\Entities\Role;
use Modules\SICA\Entities\App;

class SISIGUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener la App de SISIG para vincularla a los roles
        $app = App::where('name', 'SISIG')->first();
        $appId = $app ? $app->id : null;

        // Limpiar roles antiguos o innecesarios
        Role::whereIn('slug', ['sisig.admin', 'sisig.instructor', 'sisig.aprendiz', 'aprendiz_sisig'])->delete();

        // 2. Registrar únicamente los roles de gestión exclusivos de SISIG
        $roleAdmin = Role::updateOrCreate(['slug' => 'admin_sisig'], [
            'name' => 'Administrador SISIG',
            'description' => 'Administrador exclusivo del módulo SISIG',
            'description_english' => 'Exclusive administrator of the SISIG module',
            'full_access' => 'No',
            'app_id' => $appId
        ]);

        $roleEditor = Role::updateOrCreate(['slug' => 'editor_sisig'], [
            'name' => 'Editor SISIG',
            'description' => 'Editor de contenidos formativos y evaluaciones del módulo SISIG',
            'description_english' => 'Editor of training content and evaluations in SISIG module',
            'full_access' => 'No',
            'app_id' => $appId
        ]);

        // 3. Crear Persona y Usuario: ADMINISTRADOR SISIG
        $personAdmin = Person::updateOrCreate(['document_number' => 1000000001], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'CRISTIAN CAMILO',
            'first_last_name' => 'RAMÍREZ',
            'second_last_name' => 'TORRES',
            'personal_email' => 'admin@sena.edu.co',
            'sena_email' => 'admin@sena.edu.co',
        ]);

        $userAdmin = User::updateOrCreate(['email' => 'admin@sena.edu.co'], [
            'name' => 'Cristian Camilo Ramírez Torres',
            'nickname' => 'admin_sisig',
            'person_id' => $personAdmin->id,
            'password' => Hash::make('12345678'),
        ]);
        $userAdmin->roles()->sync([$roleAdmin->id]);

        // 4. Crear Persona y Usuario: EDITOR SISIG
        $personEditor = Person::updateOrCreate(['document_number' => 1000000002], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'EDITOR',
            'first_last_name' => 'DE CONTENIDOS',
            'second_last_name' => 'SISIG',
            'personal_email' => 'editor@sena.edu.co',
            'sena_email' => 'editor@sena.edu.co',
        ]);

        $userEditor = User::updateOrCreate(['email' => 'editor@sena.edu.co'], [
            'name' => 'Editor de Contenidos SISIG',
            'nickname' => 'editor_sisig',
            'person_id' => $personEditor->id,
            'password' => Hash::make('12345678'),
        ]);
        $userEditor->roles()->sync([$roleEditor->id]);

        // 5. Crear Persona y Usuario: APRENDIZ (Cualquier aprendiz de SENA Empresa)
        $personAprendiz = Person::updateOrCreate(['document_number' => 1000000003], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'APRENDIZ',
            'first_last_name' => 'SENA',
            'second_last_name' => 'EMPRESA',
            'personal_email' => 'aprendiz@soy.sena.edu.co',
            'sena_email' => 'aprendiz@soy.sena.edu.co',
        ]);

        $userAprendiz = User::updateOrCreate(['email' => 'aprendiz@soy.sena.edu.co'], [
            'name' => 'Aprendiz SENA Empresa',
            'nickname' => 'aprendiz',
            'person_id' => $personAprendiz->id,
            'password' => Hash::make('12345678'),
        ]);
        $userAprendiz->roles()->sync([]); // Sin rol administrativo especial

        // Registrar en tabla 'apprentices'
        DB::table('apprentices')->updateOrInsert(
            ['person_id' => $personAprendiz->id],
            [
                'course_id' => 2800001,
                'apprentice_status' => 'En formación',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
