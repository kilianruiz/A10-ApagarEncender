<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;
use App\Models\Categoria;

class SubcategoriaSeeder extends Seeder
{
    public function run()
    {
        $subcategorias = [
            'Software' => [
                'Aplicació gestió administrativa',
                'Accés remot',
                'Aplicació de videoconferència',
            ],
            'Hardware' => [
                'Problema amb el teclat',
                'El ratolí no funciona',
                'Monitor no s\'encén',
                'Imatge de projector defectuosa',
            ],
        ];

        foreach ($subcategorias as $categoria => $subs) {
            $categoriaModel = Categoria::where('nombre', $categoria)->first();
            foreach ($subs as $sub) {
                Subcategoria::create(['nombre' => $sub, 'categoria_id' => $categoriaModel->id]);
            }
        }
    }
}
