@extends('layouts.app')

@section('title', 'Mis Incidencias')

@section('content')
<div class="container">
    <h1>Mis Incidencias</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($incidencias->isEmpty())
        <p>No tienes incidencias registradas.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha de creación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incidencias as $incidencia)
                    <tr>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ Str::limit($incidencia->descripcion, 50) }}</td>
                        <td>{{ $incidencia->estado }}</td>
                        <td>{{ $incidencia->prioridad }}</td>
                        <td>{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
