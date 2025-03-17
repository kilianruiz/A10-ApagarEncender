<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncidenciasController extends Controller
{
    // Método para mostrar las incidencias en el gestor
    public function index($nombre_sede)
    {
        $user = Auth::user();
        $sede = $user->sede()->where('localización', $nombre_sede)->first();

        if (!$sede) {
            return abort(404, 'Sede no encontrada');
        }

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

        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'resueltas', 'cerradas', 'sede', 'user'));
    }

    // Obtener incidencias por estado a través de AJAX
    public function getByStatus(Request $request)
    {
        try {
            $estado = str_replace('_', ' ', $request->query('estado'));
            $titulo = $request->query('titulo');
            $prioridad = $request->query('prioridad');
            $tecnico_id = $request->query('tecnico_id');
            $sede_id = Auth::user()->sede_id;

            $query = Incidencia::with(['user', 'categoria', 'subcategoria', 'usuarios']);

            // Aplicar filtro por estado
            $query->where('estado', $estado);

            // Filtrar por sede si el usuario tiene una asignada
            if ($sede_id) {
                $query->where('sede_id', $sede_id);
            }

            // Filtrar por título si se proporciona
            if ($titulo) {
                $query->where('titulo', 'LIKE', "%{$titulo}%");
            }

            // Filtrar por prioridad si se proporciona
            if ($prioridad) {
                $query->where('prioridad', $prioridad);
            }

            // Filtrar por técnico si se proporciona
            if ($tecnico_id) {
                $query->whereHas('usuarios', function($query) use ($tecnico_id) {
                    $query->where('users.id', $tecnico_id);
                });
            }

            $incidencias = $query->get();

            // Log para debugging
            \Log::info('Query SQL:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'estado' => $estado,
                'titulo' => $titulo,
                'prioridad' => $prioridad,
                'tecnico_id' => $tecnico_id,
                'resultados' => count($incidencias)
            ]);

            // Transformar los datos para la respuesta
            $incidencias = $incidencias->map(function ($incidencia) {
                return [
                    'id' => $incidencia->id,
                    'titulo' => $incidencia->titulo,
                    'descripcion' => $incidencia->descripcion,
                    'comentario' => $incidencia->comentario,
                    'estado' => $incidencia->estado,
                    'prioridad' => $incidencia->prioridad,
                    'user' => $incidencia->user ? $incidencia->user->name : null,
                    'categoria' => $incidencia->categoria ? $incidencia->categoria->nombre : null,
                    'subcategoria' => $incidencia->subcategoria ? $incidencia->subcategoria->nombre : null,
                    'feedback' => $incidencia->feedback,
                    'created_at' => $incidencia->created_at,
                    'tecnico_asignado' => $incidencia->usuarios->map(function ($usuario) {
                        return [
                            'id' => $usuario->id,
                            'name' => $usuario->name
                        ];
                    })
                ];
            });

            return response()->json($incidencias);
        } catch (\Exception $e) {
            \Log::error('Error en getByStatus:', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Asignar incidencia
    public function asignarIncidencia(Request $request)
    {
        // Validación de datos
        $request->validate([
            'incidencia_id' => 'required|exists:incidencias,id',
            'tecnico_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // 1. Obtener la incidencia
            $incidencia = Incidencia::findOrFail($request->incidencia_id);

            // 2. Actualizar el estado de la incidencia
            $incidencia->update([
                'estado' => 'asignada'
            ]);

            // 3. Insertar en la tabla incidencia_usuario
            DB::table('incidencia_usuario')->insert([
                'titulo' => $incidencia->titulo,
                'comentario' => 'Incidencia asignada al técnico',
                'imagen' => $incidencia->imagen ?? '',
                'user_id' => $request->tecnico_id,
                'incidencia_id' => $request->incidencia_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Incidencia asignada correctamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al asignar incidencia:', [
                'error' => $e->getMessage(),
                'incidencia_id' => $request->incidencia_id,
                'tecnico_id' => $request->tecnico_id,
            ]);

            return response()->json(['error' => 'Ocurrió un error al asignar la incidencia'], 500);
        }
    }

    // Obtener técnicos disponibles
    public function obtenerTecnicos()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $tecnicos = \App\Models\User::where('role', 'tecnico')->get();
        } else {
            $tecnicos = \App\Models\User::where('jefe_id', $user->id)->get();
        }

        if ($tecnicos->isEmpty()) {
            return response()->json(['error' => 'No tienes técnicos asignados'], 404);
        }

        return response()->json($tecnicos);
    }
}
