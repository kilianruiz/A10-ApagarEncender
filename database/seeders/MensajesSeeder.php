<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mensaje;

class MensajesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mensajes = [
            [
                'mensaje' => 'La impresora ya fue reparada y funciona correctamente.',
                'incidencia_id' => 1, // ID de la incidencia a la que se le está enviando el mensaje
                'id_usuario_emisor' => 3, // ID del usuario que emite el mensaje
                'id_usuario_receptor' => 12, // ID del usuario receptor del mensaje
            ],
            [
                'mensaje' => 'El error en el login se ha corregido, ahora los usuarios pueden iniciar sesión.',
                'incidencia_id' => 2,
                'id_usuario_emisor' => 3,
                'id_usuario_receptor' => 12,
            ],
            [
                'mensaje' => 'La conexión a internet en la sede principal ha sido restaurada.',
                'incidencia_id' => 3,
                'id_usuario_emisor' => 3,
                'id_usuario_receptor' => 12,
            ],
            [
                'mensaje' => 'Se ha encontrado la causa del problema en el sistema de facturación, se están aplicando los cambios.',
                'incidencia_id' => 4,
                'id_usuario_emisor' => 3,
                'id_usuario_receptor' => 12,
            ],
            [
                'mensaje' => 'El monitor ha sido reemplazado y ya está funcionando correctamente.',
                'incidencia_id' => 5,
                'id_usuario_emisor' => 12,
                'id_usuario_receptor' => 3,
            ],
            [
                'mensaje' => 'La incidencia relacionada con el monitor ha sido cerrada, todo está en orden.',
                'incidencia_id' => 6,
                'id_usuario_emisor' => 12,
                'id_usuario_receptor' => 3,
            ],
        ];

        foreach ($mensajes as $mensaje) {
            Mensaje::create($mensaje);
        }
    }
}
