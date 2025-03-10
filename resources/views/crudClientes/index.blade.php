@extends('../layouts.layout')

@section('title', 'Mis Incidencias')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container">
    <h1>Mis Incidencias</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <button id="btn-crear" name="btn-crear" class="btn btn-primary mb-3">Crear</button>

    <table class="table table-striped" id="incidenciasTable">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Las filas de la tabla se cargarán aquí con AJAX -->
        </tbody>
    </table>
</div>

<!-- Modal para ver la incidencia en grande -->
<div class="modal fade" id="viewIncidenciaModal" tabindex="-1" role="dialog" aria-labelledby="viewIncidenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewIncidenciaModalLabel">Detalles de la Incidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="incidenciaDetails">
                <!-- Los detalles de la incidencia se cargarán aquí con JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script>
    // Función para cargar la tabla de incidencias
    function cargarIncidencias() {
        fetch('/incidencias')
            .then(response => {
                if (!response.ok) {
                    throw new Error('No se pudieron cargar las incidencias');
                }
                return response.json();
            })
            .then(data => {
                const tableBody = document.getElementById('incidenciasTable').getElementsByTagName('tbody')[0];
                tableBody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevas filas
                
                data.forEach(incidencia => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${incidencia.titulo}</td>
                        <td>${incidencia.descripcion.length > 50 ? incidencia.descripcion.substring(0, 50) + '...' : incidencia.descripcion}</td>
                        <td>${incidencia.estado}</td>
                        <td>${incidencia.prioridad}</td>
                        <td>${new Date(incidencia.created_at).toLocaleDateString()} ${new Date(incidencia.created_at).toLocaleTimeString()}</td>
                        <td>
                            <button class="btn btn-info" data-id="${incidencia.id}" onclick="verIncidencia(${incidencia.id})">
                                <i class="fas fa-search"></i> Ver
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error al cargar las incidencias:', error);
                alert('Error al cargar las incidencias.');
            });
    }

    // Función para ver los detalles de la incidencia
    function verIncidencia(id) {
        fetch(`/incidencia/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los detalles de la incidencia');
                }
                return response.json();
            })
            .then(data => {
                const details = `
                    <p><strong>Título:</strong> ${data.titulo}</p>
                    <p><strong>Descripción:</strong> ${data.descripcion}</p>
                    <p><strong>Estado:</strong> ${data.estado}</p>
                    <p><strong>Prioridad:</strong> ${data.prioridad}</p>
                    <p><strong>Fecha de creación:</strong> ${new Date(data.created_at).toLocaleDateString()} ${new Date(data.created_at).toLocaleTimeString()}</p>
                    <p><strong>Comentarios:</strong> ${data.comentarios || 'No hay comentarios'}</p>
                `;
                document.getElementById('incidenciaDetails').innerHTML = details;
                var modal = new bootstrap.Modal(document.getElementById('viewIncidenciaModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles de la incidencia.');
            });
    }

    // Cargar la tabla cuando se carga la página
    document.addEventListener('DOMContentLoaded', cargarIncidencias);
</script>

@endsection
