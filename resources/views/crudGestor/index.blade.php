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
            <button class="nav-link active" data-status="sin_asignar" data-bs-toggle="tab" data-bs-target="#sinAsignar" type="button">Sin asignar</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="asignada" data-bs-toggle="tab" data-bs-target="#asignada" type="button">Asignadas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="en_proceso" data-bs-toggle="tab" data-bs-target="#enProceso" type="button">En proceso</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="resuelta" data-bs-toggle="tab" data-bs-target="#resuelta" type="button">Resueltas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="cerrada" data-bs-toggle="tab" data-bs-target="#cerrada" type="button">Cerradas</button>
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
                <tbody id="tabla-sin_asignar"></tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="asignada" role="tabpanel">
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
                <tbody id="tabla-asignada"></tbody>
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
                <tbody id="tabla-en_proceso"></tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="resuelta" role="tabpanel">
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
                <tbody id="tabla-resuelta"></tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="cerrada" role="tabpanel">
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
                <tbody id="tabla-cerrada"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function cargarIncidencias(estado) {
            let estadoNormalizado = estado.replace(/\s/g, "_"); // Normaliza el estado
            let url = `/api/incidencias?estado=${estadoNormalizado}`; // Ruta corregida

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la respuesta del servidor");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`Datos recibidos para el estado: ${estado}`, data);
                    
                    let tabla = document.getElementById(`tabla-${estadoNormalizado}`);
                    tabla.innerHTML = ""; // Limpiar tabla

                    if (data.length === 0) {
                        tabla.innerHTML = "<tr><td colspan='6'>No hay incidencias en este estado.</td></tr>";
                    } else {
                        data.forEach(incidencia => {
                            let fila = document.createElement("tr");
                            fila.innerHTML = `
                                <td>${incidencia.id}</td>
                                <td>${incidencia.titulo}</td>
                                <td>${incidencia.descripcion}</td>
                                <td>${incidencia.estado}</td>
                                <td>${incidencia.prioridad}</td>
                                <td>${incidencia.created_at}</td>
                            `;
                            tabla.appendChild(fila);
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al obtener incidencias:", error);
                });
        }

        // Cargar incidencias al cambiar de pestaña
        document.querySelectorAll(".nav-link").forEach(tab => {
            tab.addEventListener("shown.bs.tab", function (event) {
                let estado = event.target.getAttribute("data-status");
                cargarIncidencias(estado);
            });
        });

        // Cargar la primera pestaña al inicio
        let estadoInicial = document.querySelector(".nav-link.active").getAttribute("data-status");
        cargarIncidencias(estadoInicial);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>