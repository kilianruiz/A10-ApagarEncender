<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncidenciasController extends Controller
{
    // Pagina de incidencias
    public function index()
    {
        return view('incidencias');
    }

    // Pagina para crear incidencias
    public function crear()
    {
        return view('crear_incidencia');
    }

    // Pagina para ver una incidencia
    public function ver($id)
    {
        // Buscar la incidencia por su ID
        $id_incidencia = Incidencia::findOrFail($id);

        // Pasar la incidencia a la vista
        return view('ver_incidencia', ['incidencia' => $id_incidencia]);
    }
}