<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar datos del formulario
        $request->validate([
            'nombre_usuario' => 'required|string',
            'pwd' => 'required',
        ]);

        // Usar 'name' en lugar de 'nombre_usuario' para la autenticación
        if (Auth::attempt(['name' => $request->nombre_usuario, 'password' => $request->pwd])) {
            // Si las credenciales son válidas, regenera la sesión para protegerla contra ataques
            $request->session()->regenerate();

            $user = Auth::user(); // Obtener el usuario autenticado

            if ($user->role_id == 1) {
                return redirect()->route('crudAdmin.index');
            } elseif($user->role_id == 2) {
                return redirect('/incidencias');
            } else {
                return redirect()->back()->with('error', 'Rol no válido');
            }
        }

        // Si la autenticación falla, retornar con error
        return back()->withErrors(['nombre_usuario' => 'Las credenciales no son correctas']);
    }

    public function logout(Request $request)
    {
        // Cerrar la sesión
        Auth::logout();
        // Invalidar la sesión para evitar que se reutilice
        $request->session()->invalidate();
        // Generar un nuevo token para prevenir ataques
        $request->session()->regenerateToken();
        // Redirigir al login
        return redirect()->route('login');
    }
}

