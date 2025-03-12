document.addEventListener('DOMContentLoaded', function() {
    cargarComentarios();
    
    // Agregar listeners para los cambios de pestaña
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            if (e.target.id === 'historial-tab') {
                cargarHistorial();
            } else {
                cargarComentarios();
            }
        });
    });
});

function cargarComentarios() {
    fetch('/tecnicos/comentarios')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('comentarios-body');
            const noComentarios = document.getElementById('no-comentarios');
            
            if (data.length === 0) {
                tbody.innerHTML = '';
                noComentarios.classList.remove('d-none');
                return;
            }

            noComentarios.classList.add('d-none');
            tbody.innerHTML = data.map(comentario => {
                return `
                <tr>
                    <td>${comentario.id}</td>
                    <td>${comentario.titulo}</td>
                    <td>${comentario.comentario.substring(0, 50)}${comentario.comentario.length > 50 ? '...' : ''}</td>
                    <td>
                        ${comentario.imagen 
                            ? `<img src="/img${comentario.imagen}" alt="Imagen" width="50">` 
                            : 'Sin imagen'}
                    </td>
                    <td>${new Date(comentario.created_at).toLocaleString('es')}</td>
                    <td>
                        <select class="form-select form-select-sm estado-select" 
                                onchange="cambiarEstado(${comentario.incidencia.id}, this.value)">
                            <option value="asignada" ${comentario.incidencia.estado === 'asignada' ? 'selected' : ''} ${comentario.incidencia.estado === 'en proceso' ? 'disabled' : ''}>Asignada</option>
                            <option value="en proceso" ${comentario.incidencia.estado === 'en proceso' ? 'selected' : ''}>En proceso</option>
                        </select>
                    </td>
                    <td>
                        <button onclick="mostrarFormularioResolucion(${comentario.incidencia.id})" 
                                class="btn btn-primary btn-sm"
                                ${comentario.incidencia.estado !== 'en proceso' ? 'disabled' : ''}>
                            Resolver
                        </button>
                    </td>
                </tr>
            `}).join('');
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar las incidencias pendientes'
            });
        });
}

function cambiarEstado(incidenciaId, nuevoEstado) {
    fetch('/tecnicos/cambiar-estado', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            incidencia_id: incidenciaId,
            estado: nuevoEstado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Estado actualizado correctamente',
                timer: 1500,
                showConfirmButton: false
            });
            cargarComentarios();
        } else {
            throw new Error(data.message || 'Error al actualizar el estado');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al actualizar el estado'
        });
    });
}

function cargarHistorial() {
    fetch('/tecnicos/historial')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('historial-body');
            const noHistorial = document.getElementById('no-historial');
            
            if (data.length === 0) {
                tbody.innerHTML = '';
                noHistorial.classList.remove('d-none');
                return;
            }

            noHistorial.classList.add('d-none');
            tbody.innerHTML = data.map(item => `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.titulo}</td>
                    <td>${item.comentario.substring(0, 50)}${item.comentario.length > 50 ? '...' : ''}</td>
                    <td>${item.incidencia.feedback}</td>
                    <td>${new Date(item.incidencia.updated_at).toLocaleString('es')}</td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar el historial'
            });
        });
}

function mostrarFormularioResolucion(incidenciaId) {
    Swal.fire({
        title: 'Resolver Incidencia',
        html: `
            <textarea id="feedback" class="form-control" 
                      placeholder="Ingrese el feedback de la resolución" 
                      rows="4"></textarea>
        `,
        showCancelButton: true,
        confirmButtonText: 'Resolver',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const feedback = document.getElementById('feedback').value;
            if (!feedback) {
                Swal.showValidationMessage('El feedback es requerido');
                return false;
            }
            return feedback;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            resolverIncidencia(incidenciaId, result.value);
        }
    });
}

function resolverIncidencia(incidenciaId, feedback) {
    const data = {
        incidencia_id: incidenciaId,
        feedback: feedback
    };

    fetch('/tecnicos/resolver-incidencia', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message
            }).then(() => {
                cargarComentarios(); // Recargamos la lista de pendientes
                cargarHistorial();   // Recargamos el historial
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al resolver la incidencia'
        });
    });
}
