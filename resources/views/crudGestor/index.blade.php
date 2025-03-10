<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Lista de Incidencias de la sede de Barcelona</h1>

    <h2 class="mt-4">Incidencias Sin Asignar</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sin_asignar as $incidencia_sin_asignar)
                <tr>
                    <td>{{ $incidencia_sin_asignar->id }}</td>
                    <td>{{ $incidencia_sin_asignar->titulo }}</td>
                    <td>{{ $incidencia_sin_asignar->descripcion }}</td>
                    <td>{{ $incidencia_sin_asignar->estado }}</td>
                    <td>{{ $incidencia_sin_asignar->prioridad }}</td>
                    <td>{{ $incidencia_sin_asignar->created_at }}</td>
                    {{-- <td>
                        <a href="{{ route('incidencias.ver', $incidencia->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabla de Incidencias Asignadas --}}
    <h2 class="mt-4">Incidencias Asignadas</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asignadas as $incidencia_asignada)
                <tr>
                    <td>{{ $incidencia_asignada->id }}</td>
                    <td>{{ $incidencia_asignada->titulo }}</td>
                    <td>{{ $incidencia_asignada->descripcion }}</td>
                    <td>{{ $incidencia_asignada->estado }}</td>
                    <td>{{ $incidencia_asignada->prioridad }}</td>
                    <td>{{ $incidencia_asignada->created_at }}</td>
                    {{-- <td>
                        <a href="{{ route('incidencias.ver', $incidencia->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
