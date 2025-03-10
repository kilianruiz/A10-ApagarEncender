<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Incidencia;

class IncidenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $incidencias = [
            [
                'titulo' => 'Fallo en el sistema de impresión',
                'descripcion' => 'La impresora no imprime correctamente los documentos.',
                'comentario' => null,
                'estado' => 'sin_asignar',
                'prioridad' => 'alta',
                'user_id' => 58, // ID del usuario que reporta la incidencia
                'sede_id' => 1, // ID de la sede donde ocurre la incidencia
                'subcategoria_id' => 3, // ID de la subcategoría relacionada
            ],
            [
                'titulo' => 'Error en el login',
                'descripcion' => 'Los usuarios no pueden iniciar sesión en el sistema.',
                'comentario' => 'Se ha intentado reiniciar el servidor sin éxito.',
                'estado' => 'asignada',
                'prioridad' => 'media',
                'user_id' => 58,
                'sede_id' => 2,
                'imagen' => null,
                'subcategoria_id' => 5,
            ],
            [
                'titulo' => 'Pérdida de conexión a internet',
                'descripcion' => 'La sede principal ha perdido la conexión a internet.',
                'comentario' => null,
                'estado' => 'en_proceso',
                'prioridad' => 'alta',
                'user_id' => 58,
                'sede_id' => 1,
                'imagen' => null,
                'subcategoria_id' => 7,
            ],
            [
                'titulo' => 'Problema con el software de facturación',
                'descripcion' => 'El sistema de facturación no genera PDFs correctamente.',
                'comentario' => 'Se está investigando el problema.',
                'estado' => 'resuelta',
                'prioridad' => 'baja',
                'user_id' => 58,
                'sede_id' => 3,
                'imagen' => null,
                'subcategoria_id' => 2,
            ],
            [
                'titulo' => 'Monitor no enciende',
                'descripcion' => 'El monitor de la oficina B no enciende después de una tormenta eléctrica.',
                'comentario' => null,
                'estado' => 'cerrada',
                'prioridad' => 'media',
                'user_id' => 58,
                'sede_id' => 3,
                'imagen' => null,
                'subcategoria_id' => 6,
            ]
        ];

        foreach ($incidencias as $incidencia) {
            Incidencia::create($incidencia);
        }
    }
}
