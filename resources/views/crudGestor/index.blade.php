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

    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="incidenciasTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sinAsignar-tab" data-bs-toggle="tab" data-bs-target="#sinAsignar" type="button" role="tab">Sin Asignar</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="asignadas-tab" data-bs-toggle="tab" data-bs-target="#asignadas" type="button" role="tab">Asignadas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab">Historial</button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content mt-3" id="incidenciasTabsContent">
        <!-- Tabla de Incidencias Sin Asignar -->
        <div class="tab-pane fade show active" id="sinAsignar" role="tabpanel">
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
                    @foreach($sin_asignar as $incidencia)
                        <tr>
                            <td>{{ $incidencia->id }}</td>
                            <td>{{ $incidencia->titulo }}</td>
                            <td>{{ $incidencia->descripcion }}</td>
                            <td>{{ $incidencia->estado }}</td>
                            <td>{{ $incidencia->prioridad }}</td>
                            <td>{{ $incidencia->created_at }}</td>
                            {{-- <td>
                                <a href="{{ route('incidencias.ver', $incidencia->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tabla de Incidencias Asignadas -->
        <div class="tab-pane fade" id="asignadas" role="tabpanel">
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
                    @foreach($asignadas as $incidencia)
                        <tr>
                            <td>{{ $incidencia->id }}</td>
                            <td>{{ $incidencia->titulo }}</td>
                            <td>{{ $incidencia->descripcion }}</td>
                            <td>{{ $incidencia->estado }}</td>
                            <td>{{ $incidencia->prioridad }}</td>
                            <td>{{ $incidencia->created_at }}</td>
                            {{-- <td>
                                <a href="{{ route('incidencias.ver', $incidencia->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="historico" role="tabpanel">
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
                    @foreach($resueltas as $incidencia)
                        <tr>
                            <td>{{ $incidencia->id }}</td>
                            <td>{{ $incidencia->titulo }}</td>
                            <td>{{ $incidencia->descripcion }}</td>
                            <td>{{ $incidencia->estado }}</td>
                            <td>{{ $incidencia->prioridad }}</td>
                            <td>{{ $incidencia->created_at }}</td>
                            {{-- <td>
                                <a href="{{ route('incidencias.ver', $incidencia->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>