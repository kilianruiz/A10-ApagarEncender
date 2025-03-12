<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_usuario' => 'required|string',
            'pwd' => 'required',
        ]);

        // Intentar autenticar al usuario con las credenciales proporcionadas
        if (Auth::attempt(['name' => $request->nombre_usuario, 'password' => $request->pwd])) {
            // Si las credenciales son válidas, regenera la sesión para protegerla contra ataques
            $request->session()->regenerate();

            $user = Auth::user(); // Obtener el usuario autenticado

            // Comprobar el rol del usuario y redirigir según corresponda
            if ($user->role_id == 1) {
                return redirect('/admin');
            } elseif ($user->role_id == 2) {
                return redirect('/clientes/' . $user->id); // Usar el ID del usuario autenticado
            } elseif ($user->role_id == 3) {
                // Obtener la sede del usuario autenticado
                $sede = $user->sede;  // Asegúrate de tener la relación de "sede" en el modelo User

                if ($sede) {
                    // Redirigir a la página del gestor con el nombre de la sede
                    return redirect('/gestor/' . $sede->localización);
                } else {
                    // Si no tiene sede, redirigir a una página de error
                    return redirect('/error-autenticacion');
                }
            } elseif ($user->role_id == 4) {
                return redirect('/tecnicos');
            } else {
                // Si no es ninguno de los roles conocidos, redirigir a una página de error
                return redirect('/error-autenticacion');
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
        return redirect('/index');
    }
}