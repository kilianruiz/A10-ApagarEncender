<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Administrador', 'Cliente', 'Gestor', 'Tecnico'];
        foreach ($roles as $role) {
            Role::create(['nombre' => $role]);
        }
    }
}
