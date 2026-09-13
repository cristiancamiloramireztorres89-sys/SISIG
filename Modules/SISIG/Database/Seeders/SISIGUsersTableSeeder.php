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
        // 1. Obtener la App de SISIG si existe en SICA
        $app = App::where('name', 'SISIG')->first();
        $appId = $app ? $app->id : null;

        // 2. Registrar Roles de SISIG
        $roleAdmin = Role::updateOrCreate(['slug' => 'sisig.admin'], [
            'name' => 'Administrador SISIG',
            'description' => 'Administrador general del módulo SISIG',
            'description_english' => 'General Administrator of the SISIG module',
            'full_access' => 'Si',
            'app_id' => $appId
        ]);

        $roleInstructor = Role::updateOrCreate(['slug' => 'sisig.instructor'], [
            'name' => 'Instructor SIG',
            'description' => 'Instructor encargado de crear secciones, quices y revisar aprendices',
            'description_english' => 'Instructor in charge of sections, quizzes and review',
            'full_access' => 'No',
            'app_id' => $appId
        ]);

        $roleAprendiz = Role::updateOrCreate(['slug' => 'sisig.aprendiz'], [
            'name' => 'Aprendiz SENA Empresa',
            'description' => 'Aprendiz que realiza la inducción y presenta evaluaciones SIG',
            'description_english' => 'Apprentice taking SIG induction and evaluations',
            'full_access' => 'No',
            'app_id' => $appId
        ]);

        // 3. Crear Persona y Usuario: ADMINISTRADOR
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
            'nickname' => 'admin',
            'person_id' => $personAdmin->id,
            'password' => Hash::make('12345678'),
        ]);
        $userAdmin->roles()->syncWithoutDetaching([$roleAdmin->id]);

        // 4. Crear Persona y Usuario: INSTRUCTOR
        $personInstructor = Person::updateOrCreate(['document_number' => 1000000002], [
            'document_type' => 'Cédula de ciudadanía',
            'first_name' => 'INSTRUCTOR',
            'first_last_name' => 'SST Y AMBIENTAL',
            'second_last_name' => 'CEFA',
            'personal_email' => 'instructor@sena.edu.co',
            'sena_email' => 'instructor@sena.edu.co',
        ]);

        $userInstructor = User::updateOrCreate(['email' => 'instructor@sena.edu.co'], [
            'name' => 'Instructor SST y Ambiental',
            'nickname' => 'instructor',
            'person_id' => $personInstructor->id,
            'password' => Hash::make('12345678'),
        ]);
        $userInstructor->roles()->syncWithoutDetaching([$roleInstructor->id]);

        // 5. Crear Persona, Usuario y Aprendiz: APRENDIZ
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
        $userAprendiz->roles()->syncWithoutDetaching([$roleAprendiz->id]);

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
