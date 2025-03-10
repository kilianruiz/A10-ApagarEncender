<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function mostrarIncidencias($userId)
    {
        // Encuentra al usuario por ID
        $usuario = User::find($userId);
        
        if (!$usuario) {
            return redirect()->route('clientes.index')->with('error', 'Usuario no encontrado');
        }

        $incidencias = $usuario->incidencias;

        return view('crudClientes.index', compact('incidencias')); 
    }
}
