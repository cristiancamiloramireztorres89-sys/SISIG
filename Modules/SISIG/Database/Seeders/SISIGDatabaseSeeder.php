<?php

namespace Modules\SISIG\Database\Seeders;

use Illuminate\Database\Seeder;

class SISIGDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            SISIGUsersTableSeeder::class,
        ]);
    }
}
