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

            [
                'name' => 'Admin',
                'email' => 'admin@fje.edu',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 1, // Admin
                'sede_id' => null, // Todas
                'jefe_id' => null
            ],

            // Usuarios Sede Central (Barcelona)
            
            [
                'name' => 'GestorBCN',
                'email' => 'gestor@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 3, // Gestor
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Tecnico1BCN',
                'email' => 'usuario1@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico2BCN',
                'email' => 'tecnico2@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico3BCN',
                'email' => 'tecnico3@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico4BCN',
                'email' => 'tecnico4@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico5BCN',
                'email' => 'tecnico5@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico6BCN',
                'email' => 'tecnico6@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico7BCN',
                'email' => 'tecnico7@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico8BCN',
                'email' => 'tecnico8@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Tecnico9BCN',
                'email' => 'tecnico9@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 1, // Barcelona
                'jefe_id' => 2
            ],
            [
                'name' => 'Cliente1BCN',
                'email' => 'cliente1@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente2BCN',
                'email' => 'cliente2@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente3BCN',
                'email' => 'cliente3@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente4BCN',
                'email' => 'cliente4@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente5BCN',
                'email' => 'cliente5@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente6BCN',
                'email' => 'cliente6@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente7BCN',
                'email' => 'cliente7@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente8BCN',
                'email' => 'cliente8@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente9BCN',
                'email' => 'cliente9@fje.bcn',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 1, // Barcelona
                'jefe_id' => null
            ],
            
            // Usuarios Sede Este (Berlín)

            [
                'name' => 'GestorBL',
                'email' => 'gestor@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 3, // Gestor
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Tecnico1BL',
                'email' => 'usuario1@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico2BL',
                'email' => 'tecnico2@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico3BL',
                'email' => 'tecnico3@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico4BL',
                'email' => 'tecnico4@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico5BL',
                'email' => 'tecnico5@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico6BL',
                'email' => 'tecnico6@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico7BL',
                'email' => 'tecnico7@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico8BL',
                'email' => 'tecnico8@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Tecnico9BL',
                'email' => 'tecnico9@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 2, // Berlín
                'jefe_id' => 22
            ],
            [
                'name' => 'Cliente1BL',
                'email' => 'cliente1@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente2BL',
                'email' => 'cliente2@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente3BL',
                'email' => 'cliente3@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente4BL',
                'email' => 'cliente4@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente5BL',
                'email' => 'cliente5@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente6BL',
                'email' => 'cliente6@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente7BL',
                'email' => 'cliente7@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente8BL',
                'email' => 'cliente8@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente9BL',
                'email' => 'cliente9@fje.bl',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 2, // Berlín
                'jefe_id' => null
            ],

            // Usuarios Sede Oeste (Montreal)

            [
                'name' => 'GestorMT',
                'email' => 'gestor@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 3, // Gestor
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Tecnico1MT',
                'email' => 'usuario1@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico2MT',
                'email' => 'tecnico2@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico3MT',
                'email' => 'tecnico3@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico4MT',
                'email' => 'tecnico4@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico5MT',
                'email' => 'tecnico5@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico6MT',
                'email' => 'tecnico6@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico7MT',
                'email' => 'tecnico7@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico8MT',
                'email' => 'tecnico8@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Tecnico9MT',
                'email' => 'tecnico9@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 4, // Tecnico
                'sede_id' => 3, // Montreal
                'jefe_id' => 40
            ],
            [
                'name' => 'Cliente1MT',
                'email' => 'cliente1@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente2MT',
                'email' => 'cliente2@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente3MT',
                'email' => 'cliente3@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente4MT',
                'email' => 'cliente4@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente5MT',
                'email' => 'cliente5@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente6MT',
                'email' => 'cliente6@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente7MT',
                'email' => 'cliente7@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente8MT',
                'email' => 'cliente8@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ],
            [
                'name' => 'Cliente9MT',
                'email' => 'cliente9@fje.mt',
                'password' => Hash::make('QWEqwe123@'),
                'role_id' => 2, // Cliente
                'sede_id' => 3, // Montreal
                'jefe_id' => null
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
