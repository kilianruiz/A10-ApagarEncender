<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Administrador', 'Client', 'Gestor d\'equip', 'Usuari'];
        foreach ($roles as $role) {
            Role::create(['nombre' => $role]);
        }
    }
}
