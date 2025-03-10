<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Lista de Incidencias de la sede de Barcelona</h1>

    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="incidenciasTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-status="sin asignar" data-bs-toggle="tab" data-bs-target="#sinAsignar" type="button">Sin Asignar</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="asignadas" data-bs-toggle="tab" data-bs-target="#asignadas" type="button">Asignadas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="en proceso" data-bs-toggle="tab" data-bs-target="#enProceso" type="button">En Proceso</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="resueltas" data-bs-toggle="tab" data-bs-target="#resueltas" type="button">Resueltas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="cerradas" data-bs-toggle="tab" data-bs-target="#cerradas" type="button">Cerradas</button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content mt-3">
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
                    </tr>
                </thead>
                <tbody id="tabla-sinAsignar"></tbody>
            </table>
        </div>
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
                    </tr>
                </thead>
                <tbody id="tabla-asignadas"></tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="enProceso" role="tabpanel">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha de creación</th>
                    </tr>
                </thead>
                <tbody id="tabla-enProceso"></tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="resueltas" role="tabpanel">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha de creación</th>
                    </tr>
                </thead>
                <tbody id="tabla-resueltas"></tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="cerradas" role="tabpanel">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha de creación</th>
                    </tr>
                </thead>
                <tbody id="tabla-cerradas"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Cargar incidencias según el estado
        function cargarIncidencias(estado) {
            $.ajax({
                url: "/gestor/incidencias",  // Verifica que esta URL sea la correcta
                type: "GET",
                data: { estado: estado },
                success: function (data) {
                    console.log("Datos recibidos para el estado: " + estado);
                    console.log(data);  // Mostrar los datos recibidos para depuración
                
                    let estadoNormalizado = estado.replace(/\s/g, "");  // Normaliza el estado
                    let tabla = $("#tabla-" + estadoNormalizado);
                
                    // Limpiar la tabla
                    tabla.empty();
                
                    // Verificar si hay datos
                    if (data.length === 0) {
                        tabla.append("<tr><td colspan='6'>No se encontraron incidencias para este estado.</td></tr>");
                    }
                
                    // Agregar las filas de la tabla
                    data.forEach(function (incidencia) {
                        tabla.append(`
                            <tr>
                                <td>${incidencia.id}</td>
                                <td>${incidencia.titulo}</td>
                                <td>${incidencia.descripcion}</td>
                                <td>${incidencia.estado}</td>
                                <td>${incidencia.prioridad}</td>
                                <td>${incidencia.created_at}</td>
                            </tr>
                        `);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error al obtener incidencias: " + error);
                    console.log(xhr.responseText);  // Ver la respuesta completa para depurar.
                }
            });
        }

        // Cargar incidencias al cambiar de pestaña
        $(".nav-link").on("shown.bs.tab", function (e) {
            let estado = $(e.target).data("status");  // Obtener el estado de la pestaña seleccionada
            cargarIncidencias(estado);  // Cargar las incidencias correspondientes
        });

        // Cargar la primera pestaña al cargar la página
        cargarIncidencias("sin asignar");
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>