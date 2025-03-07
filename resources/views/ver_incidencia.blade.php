<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Incidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Incidencia #{{ $incidencia->id }}</h1>

        <!-- Mostrar los detalles de la incidencia -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $incidencia->titulo }}</h5>
                <p class="card-text">{{ $incidencia->descripcion }}</p>
                <p class="card-text"><strong>Categoría:</strong> {{ $incidencia->categoria->nombre }}</p>
                <p class="card-text"><strong>Creado por:</strong> {{ $incidencia->usuario->nombre }}</p>
                <p class="card-text"><small class="text-muted">Creado el {{ $incidencia->created_at }}</small></p>
            </div>
        </div>

        <!-- Botón para regresar -->
        <a href="{{ route('incidencias.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
    </div>
</body>
</html>