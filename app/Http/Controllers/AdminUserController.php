<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sede;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Iniciar la consulta con las relaciones necesarias
            $query = User::with(['role', 'sede']);

            // Filtrar por nombre si se proporciona
            if ($request->has('nombre') && $request->nombre != '') {
                $query->where('name', 'like', '%' . $request->nombre . '%');
            }

            // Filtrar por email si se proporciona
            if ($request->has('email') && $request->email != '') {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            // Filtrar por rol si se proporciona
            if ($request->has('role_id') && $request->role_id != '') {
                $query->where('role_id', $request->role_id);
            }

            // Ordenar por columna y orden si se proporcionan
            if ($request->has('sort_column') && $request->has('sort_order')) {
                $column = $request->sort_column;
                $order = $request->sort_order;

                if ($column === 'role') {
                    $query->join('roles', 'users.role_id', '=', 'roles.id')
                          ->orderBy('roles.nombre', $order);
                } else {
                    $query->orderBy($column, $order);
                }
            }

            // Obtener los usuarios
            $usuarios = $query->get();
            $roles = Role::all();
            $sedes = Sede::all();

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios,
                'roles' => $roles,
                'sedes' => $sedes
            ]);
        }

        $roles = Role::all();
        $sedes = Sede::all();
        return view('crudAdmin.index', compact('roles', 'sedes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $user = User::create($request->all());
        $usuarios = User::with(['role', 'sede'])->get();
        return response()->json(['user' => $user, 'usuarios' => $usuarios, 'message' => 'Usuario creado exitosamente.']);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);



        $user->update($request->all());
        return response()->json(['user' => $user, 'message' => 'Usuario actualizado exitosamente.']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado exitosamente.']);
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }
}