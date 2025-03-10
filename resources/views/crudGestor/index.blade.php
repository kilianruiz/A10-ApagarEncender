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
    <h1 class="mb-4">Lista de Incidencias</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Comentario</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Creador</th>
                <th>Categoria</th>
                <th>Subcategoria</th>
                <th>Fecha de creación</th>
                <th>Última actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->id }}</td>
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->descripcion }}</td>
                    <td>{{ $incidencia->comentario }}</td>
                    <td>{{ $incidencia->estado }}</td>
                    <td>{{ $incidencia->prioridad }}</td>
                    <td>{{ $incidencia->user_id }}</td>
                    <td>{{ $incidencia->subcategoria_id }}</td>
                    <td>{{ $incidencia->created_at }}</td>
                    <td>{{ $incidencia->updated_at }}</td>
                    {{-- <td>
                        <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('incidencias.destroy', $incidencia->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>