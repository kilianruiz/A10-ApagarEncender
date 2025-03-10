<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Incidencia;

class ClienteController extends Controller
{
    public function index($userId) // Recibe el ID del usuario
    {
        // Obtener las incidencias asociadas al usuario con el id proporcionado
        $incidencias = Incidencia::where('user_id', $userId)->get();

        // Pasar las incidencias a la vista
        return view('crudClientes.index', compact('incidencias'));
    }
}

