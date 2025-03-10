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
            ['nombre' => 'Sede Este', 'localización' => 'Berlín'],
            ['nombre' => 'Sede Oeste', 'localización' => 'Montreal']
        ];

        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
