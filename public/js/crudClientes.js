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
                
                // Cambiar el color de la prioridad
                let prioridadColor = '';
                if (incidencia.prioridad === 'alta') {
                    prioridadColor = 'text-danger'; // Rojo
                } else if (incidencia.prioridad === 'media') {
                    prioridadColor = 'text-warning'; // Naranja
                } else if (incidencia.prioridad === 'baja') {
                    prioridadColor = 'text-success'; // Verde
                }

                row.innerHTML = `
                    <td>${incidencia.titulo}</td>
                    <td>${incidencia.descripcion.length > 50 ? incidencia.descripcion.substring(0, 50) + '...' : incidencia.descripcion}</td>
                    <td>
                        ${incidencia.imagen ? 
                            `<img src="/storage/${incidencia.imagen}" alt="Imagen de la incidencia" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">` 
                            : '<span class="text-muted">Sin imagen</span>'
                        }
                    </td>
                    <td>${incidencia.estado}</td>
                    <td class="${prioridadColor}">${incidencia.prioridad.charAt(0).toUpperCase() + incidencia.prioridad.slice(1)}</td>
                    <td>${new Date(incidencia.created_at).toLocaleDateString()} ${new Date(incidencia.created_at).toLocaleTimeString()}</td>
                    <td>
                        <button class="btn btn-info me-2" data-id="${incidencia.id}" onclick="verIncidencia(${incidencia.id})">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-warning" data-id="${incidencia.id}" onclick="editarIncidencia(${incidencia.id})">
                            <i class="fas fa-edit"></i>
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
                ${data.imagen ? 
                    `<div class="text-center mb-3">
                        <img src="/storage/${data.imagen}" alt="Imagen de la incidencia" class="img-fluid rounded" style="max-height: 300px;">
                    </div>` 
                    : ''
                }
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
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los detalles de la incidencia',
                icon: 'error'
            });
        });
}

// Función para editar la incidencia
function editarIncidencia(id) {
    fetch(`/incidencia/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los detalles para editar');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('editTitulo').value = data.titulo;
            document.getElementById('editDescripcion').value = data.descripcion;
            document.getElementById('editPrioridad').value = data.prioridad;

            const form = document.getElementById('editIncidenciaForm');
            form.onsubmit = function(event) {
                event.preventDefault();

                const updatedData = {
                    titulo: document.getElementById('editTitulo').value,
                    descripcion: document.getElementById('editDescripcion').value,
                    prioridad: document.getElementById('editPrioridad').value
                };

                // Obtener el token CSRF del meta tag
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/incidencia/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(updatedData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Error en la actualización');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Incidencia actualizada correctamente',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recargar la tabla y cerrar el modal
                        cargarIncidencias();
                        bootstrap.Modal.getInstance(document.getElementById('editIncidenciaModal')).hide();
                    } else {
                        throw new Error(data.message || 'Error al actualizar');
                    }
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    
                    // Mostrar mensaje de error con SweetAlert2
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Error al actualizar la incidencia',
                        icon: 'error'
                    });
                });
            };

            var modal = new bootstrap.Modal(document.getElementById('editIncidenciaModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los detalles para editar',
                icon: 'error'
            });
        });
}

// Cargar la tabla cuando se carga la página
document.addEventListener('DOMContentLoaded', cargarIncidencias);