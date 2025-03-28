<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SedeSeeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\SubcategoriaSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MensajesSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            SedeSeeder::class,
            UserSeeder::class,
            CategoriaSeeder::class,
            SubcategoriaSeeder::class,
            IncidenciasSeeder::class,
            IncidenciasUsuariosSeeder::class,
            MensajesSeeder::class
        ]);
    }
}
