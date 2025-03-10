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
                'Aplicación de gestión administrativa',
                'Acceso remot',
                'Aplicación de videoconferencia',
            ],
            'Hardware' => [
                'Problemas con el teclado',
                'El ratón no funciona',
                'El monitor no se enciende',
                'Imagen del proyector defectuosa',
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
