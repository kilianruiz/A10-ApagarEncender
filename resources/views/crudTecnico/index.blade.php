@extends('layouts.layout')

@section('title', 'Dashboard Técnico')

@section('content')
<div class="container mt-4">
    <h2>Comentarios de Incidencias</h2>
    
    @if($comentarios->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Comentario</th>
                    <th>Imagen</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comentarios as $comentario)
                    <tr>
                        <td>{{ $comentario->id }}</td>
                        <td>{{ $comentario->titulo }}</td>
                        <td>{{ Str::limit($comentario->comentario, 50) }}</td>
                        <td>
                            @if($comentario->imagen)
                                <img src="{{ asset('/img' . $comentario->imagen) }}" alt="Imagen" width="50">
                            @else
                                Sin imagen
                            @endif
                        </td>
                        <td>{{ $comentario->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('incidencias.ver', $comentario->incidencia_id) }}" class="btn btn-primary btn-sm">Resolver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            No hay comentarios registrados.
        </div>
    @endif
</div>
@endsection