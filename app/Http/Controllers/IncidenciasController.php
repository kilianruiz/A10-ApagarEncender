<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\Incidencia;
use App\Models\User;
use Illuminate\Http\Request;

class IncidenciasController extends Controller
{
    // Crud de incidencias del Gestor
    public function index()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();

        // Si el usuario es admin (sede_id null), mostrar todas las incidencias
        if ($user->sede_id === null) {
            $sin_asignar = Incidencia::where('estado', 'sin asignar')->get();
            $asignadas = Incidencia::whereIn('estado', ['asignada', 'en proceso'])->get();
            $resueltas = Incidencia::whereIn('estado', 'resuelta')->get();
        } else {
            // Si el usuario pertenece a una sede específica, filtrar por su sede_id
            $sin_asignar = Incidencia::where('estado', 'sin asignar')
                                    ->where('sede_id', $user->sede_id)
                                    ->get();

            $asignadas = Incidencia::whereIn('estado', ['asignada', 'en proceso'])
                                    ->where('sede_id', $user->sede_id)
                                    ->get();
            
            $resueltas = Incidencia::where('estado', 'resuelta')
                                    ->where('sede_id', $user->sede_id)
                                    ->get();
        }

        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'resueltas'));
    }


    // Pagina para asignar incidencias
    // public function crear()
    // {
    //     return view('crear_incidencia');
    // }

    // Pagina para ver en detalle una incidencia
    // public function ver($id)
    // {
    //     // Buscar la incidencia por su ID
    //     $id_incidencia = Incidencia::findOrFail($id);

    //     // Pasar la incidencia a la vista
    //     return view('ver_incidencia', ['incidencia' => $id_incidencia]);
    // }
}