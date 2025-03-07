<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role_id' => 1, 'sede_id' => 1],
            ['name' => 'Client1', 'email' => 'client1@example.com', 'password' => Hash::make('password'), 'role_id' => 2, 'sede_id' => 2],
            ['name' => 'Gestor', 'email' => 'gestor@example.com', 'password' => Hash::make('password'), 'role_id' => 3, 'sede_id' => 3],
            ['name' => 'Usuari1', 'email' => 'usuari1@example.com', 'password' => Hash::make('password'), 'role_id' => 4, 'sede_id' => 4],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
