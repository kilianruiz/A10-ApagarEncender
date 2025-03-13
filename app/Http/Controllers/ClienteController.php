<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Incidencia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index($userId) // Recibe el ID del usuario
    {
        // Obtener las incidencias asociadas al usuario con el id proporcionado
        $incidencias = Incidencia::where('user_id', $userId)->get();

        // Pasar las incidencias a la vista
        return view('crudClientes.index', compact('incidencias'));
    }
    // En ClienteController.php
    public function show($id)
    {
        $incidencia = Incidencia::find($id);  // Si usas Eloquent, encuentra la incidencia por ID
        
        if (!$incidencia) {
            return response()->json(['error' => 'Incidencia no encontrada'], 404);
        }
    
        return response()->json($incidencia);
    }
    public function getIncidencias(Request $request)
    {
        $query = Incidencia::where('user_id', Auth::id());

        // Si se proporciona un estado, filtrar por ese estado
        if ($request->has('estado') && $request->input('estado') !== null) {
            $estado = $request->input('estado');
            
            // Manejar diferentes casos de estado
            switch ($estado) {
                case 'sin_asignar':
                    $query->where('estado', 'sin_asignar');
                    break;
                case 'asignadas':
                    $query->whereIn('estado', ['asignada']);
                    break;
                case 'en_proceso':
                    $query->where('estado', 'en_proceso');
                    break;
                case 'resueltas':
                    $query->where('estado', 'resuelta');
                    break;
                case 'cerradas':
                    $query->where('estado', 'cerrada');
                    break;
            }
        }

        // Manejar el ordenamiento por fecha
        $orden = $request->input('orden', 'desc'); // Por defecto, orden descendente
        $query->orderBy('created_at', $orden);

        $incidencias = $query->get();

        return response()->json($incidencias);
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:alta,media,baja',
            'imagen' => 'nullable|image|max:2048',
            // Otros campos de validación según sea necesario
        ]);

        // Procesar la imagen si es que se sube
        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('incidencias', 'public');
        }

        // Crear la nueva incidencia
        $incidencia = Incidencia::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? 'sin_asignar', // Aseguramos que 'estado' sea 'sin asignar' si no se proporciona
            'prioridad' => $request->prioridad,
            'user_id' => Auth::id(), // El usuario autenticado
            'sede_id' => 1, // Asegúrate de obtener el valor correcto para el "sede_id"
            'imagen' => $imagenPath,
            'subcategoria_id' => 1, // Asegúrate de obtener el valor correcto para el "subcategoria_id"
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Incidencia creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        try {
            // Buscar la incidencia
            $incidencia = Incidencia::findOrFail($id);

            // Verificar que el usuario autenticado sea el propietario de la incidencia
            if ($incidencia->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para actualizar esta incidencia'
                ], 403);
            }

            // Validar los datos recibidos
            $validatedData = $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'prioridad' => 'required|in:alta,media,baja'
            ]);

            // Actualizar la incidencia
            $incidencia->fill($validatedData);
            $incidencia->save();

            return response()->json([
                'success' => true,
                'message' => 'Incidencia actualizada correctamente',
                'data' => $incidencia
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la incidencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}