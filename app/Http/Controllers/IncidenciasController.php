<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenciasController extends Controller
{
    // Método para mostrar las incidencias en el gestor
    public function index($nombre_sede)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Buscar la sede por nombre
        $sede = $user->sede()->where('nombre', $nombre_sede)->first();

        if (!$sede) {
            return abort(404, 'Sede no encontrada');
        }

        // Obtener las incidencias filtradas por la sede del usuario
        $sin_asignar = Incidencia::where('estado', 'sin asignar')
                                ->where('sede_id', $sede->id)
                                ->get();

        $asignadas = Incidencia::whereIn('estado', ['asignada', 'en proceso'])
                                ->where('sede_id', $sede->id)
                                ->get();
        
        $resueltas = Incidencia::where('estado', 'resuelta')
                                ->where('sede_id', $sede->id)
                                ->get();

        $cerradas = Incidencia::where('estado', 'cerrada')
                                ->where('sede_id', $sede->id)
                                ->get();

        // Retornar la vista con las incidencias filtradas
        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'resueltas', 'cerradas', 'sede'));
    }

    // Obtener incidencias por estado a través de AJAX
    public function getByStatus(Request $request)
    {
        $estado = str_replace("_", " ", $request->input('estado')); // Reemplazar "_" por espacios

        if (!$estado) {
            return response()->json(['error' => 'Estado no proporcionado'], 400);
        }

        $user = Auth::user(); // Obtener el usuario autenticado
        $sede_id = $user->sede_id;

        if ($sede_id === null) {
            // Admin puede ver todas las incidencias de todas las sedes
            $incidencias = Incidencia::where('estado', $estado)->get();
        } else {
            // Filtrar las incidencias por sede_id del usuario autenticado
            $incidencias = Incidencia::where('estado', $estado)
                                    ->where('sede_id', $sede_id)
                                    ->get();
        }

        return response()->json($incidencias);
    }
}