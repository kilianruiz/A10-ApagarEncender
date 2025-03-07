<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedeSeeder extends Seeder
{
    public function run()
    {
        $sedes = [
            ['nombre' => 'Sede Central', 'localización' => 'Barcelona'],
            ['nombre' => 'Sede Norte', 'localización' => 'Girona'],
            ['nombre' => 'Sede Sur', 'localización' => 'Tarragona'],
            ['nombre' => 'Sede Este', 'localización' => 'Lleida'],
        ];

        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
