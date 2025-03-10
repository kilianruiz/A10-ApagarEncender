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
        return view('crudGestor.index');
    }

    // Pagina para asignar incidencias
    public function crear()
    {
        return view('crear_incidencia');
    }

    // Pagina para ver en detalle una incidencia
    public function ver($id)
    {
        // Buscar la incidencia por su ID
        $id_incidencia = Incidencia::findOrFail($id);

        // Pasar la incidencia a la vista
        return view('ver_incidencia', ['incidencia' => $id_incidencia]);
    }
}