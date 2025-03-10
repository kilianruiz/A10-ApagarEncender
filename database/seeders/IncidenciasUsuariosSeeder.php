<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\IncidenciaUsuario;

class IncidenciasUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar 5 registros en la tabla incidencia_usuario
        IncidenciaUsuario::create([
            'titulo' => 'Comentario sobre fallo en impresora',
            'comentario' => 'El usuario reportó que la impresora no imprime correctamente.',
            'imagen' => 'impresora_comentario.jpg',
            'user_id' => 3, // Tecnico Barcelona
            'incidencia_id' => 1, // ID de la incidencia relacionada
        ]);

        IncidenciaUsuario::create([
            'titulo' => 'Actualización sobre error de login',
            'comentario' => 'Se está investigando el problema con el sistema de autenticación.',
            'imagen' => 'login_investigacion.png',
            'user_id' => 22, // Tecnico Berlin
            'incidencia_id' => 2,
        ]);

        IncidenciaUsuario::create([
            'titulo' => 'Comentario sobre pérdida de internet',
            'comentario' => 'El proveedor de internet está trabajando en la restauración del servicio.',
            'imagen' => 'internet_proveedor.jpg',
            'user_id' => 41, // Tecnico Montreal
            'incidencia_id' => 3,
        ]);

        IncidenciaUsuario::create([
            'titulo' => 'Comentario sobre pérdida de internet',
            'comentario' => 'El proveedor de internet está trabajando en la restauración del servicio.',
            'imagen' => 'internet_proveedor.jpg',
            'user_id' => 41, // Tecnico Montreal
            'incidencia_id' => 6,
        ]);

    }
}
