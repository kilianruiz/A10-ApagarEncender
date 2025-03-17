<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;
use App\Models\Incidencia;

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

            // Filtrar por sede si se proporciona
            if ($request->has('sede_id') && $request->sede_id != '') {
                $query->where('sede_id', $request->sede_id);
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

            // Obtener los usuarios con paginación
            $usuarios = $query->paginate(10); // Cambia 10 por el número de usuarios por página que desees

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios->items(),
                'roles' => Role::all(),
                'sedes' => Sede::all(),
                'pagination' => [
                    'total' => $usuarios->total(),
                    'current_page' => $usuarios->currentPage(),
                    'per_page' => $usuarios->perPage(),
                    'last_page' => $usuarios->lastPage(),
                    'from' => $usuarios->firstItem(),
                    'to' => $usuarios->lastItem()
                ]
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
            'sede_id' => 'required|exists:sedes,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        DB::transaction(function () use ($request, $user) {
            $data = $request->only(['name', 'email', 'role_id', 'sede_id']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
    
            $user->update($data);
        });
    
        return response()->json(['user' => $user, 'message' => 'Usuario actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
    
            // Buscar un usuario random de la misma sede
            $nuevoUsuario = User::where('sede_id', $user->sede_id)
                                ->where('id', '!=', $user->id)
                                ->inRandomOrder()
                                ->first();
    
            // Si no hay usuarios en la misma sede, elegir cualquier usuario disponible
            if (!$nuevoUsuario) {
                $nuevoUsuario = User::where('id', '!=', $user->id)
                                    ->inRandomOrder()
                                    ->first();
            }
    
            // Si aún no se encontró un usuario, asignar al usuario con ID = 1 (fallback)
            $nuevoUsuarioId = $nuevoUsuario ? $nuevoUsuario->id : 1;
    
            // Reasignar incidencias
            Incidencia::where('user_id', $user->id)->update(['user_id' => $nuevoUsuarioId]);
            DB::table('incidencia_usuario')->where('user_id', $user->id)->update(['user_id' => $nuevoUsuarioId]);
    
            // Eliminar referencias en jefe_id
            User::where('jefe_id', $user->id)->update(['jefe_id' => null]);
    
            // Finalmente, eliminar el usuario
            $user->delete();
    
            DB::commit();
    
            return response()->json([
                'message' => "Usuario eliminado y sus incidencias fueron reasignadas al usuario con ID $nuevoUsuarioId."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    public function edit($id)
    {
        // Buscar el usuario o devolver un error 404 si no existe
        $user = User::with('role', 'sede')->find($id);
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        // Retornar los datos del usuario en formato JSON
        return response()->json($user);
    }
    
    public function getUserIncidencias($id)
    {
        $incidencias = Incidencia::where('user_id', $id)
            ->orWhereHas('usuarios', function($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->get();
        return response()->json($incidencias);
    }
}