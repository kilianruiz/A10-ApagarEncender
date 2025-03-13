document.addEventListener('DOMContentLoaded', function() {
    cargarComentarios();
    
    // Configurar fecha mínima en el filtro
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filtroFecha').min = today;
    document.getElementById('filtroFecha').value = today;
    
    // Agregar event listeners para filtros automáticos
    document.getElementById('filtroEstado').addEventListener('change', () => {
        aplicarFiltros();
    });
    
    document.getElementById('filtroFecha').addEventListener('change', () => {
        aplicarFiltros();
    });
    
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

function cargarComentarios(filtros = {}) {
    // Construir URL con los filtros usando URLSearchParams
    const url = new URL('/tecnicos/comentarios', window.location.origin);
    const params = new URLSearchParams();
    
    if (filtros.estado) {
        params.append('estado', filtros.estado);
    }
    if (filtros.fecha) {
        params.append('fecha', filtros.fecha);
    }
    
    if (params.toString()) {
        url.search = params.toString();
    }

    console.log('URL de la petición:', url.toString());

    fetch(url)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data); // Para depuración
            const tbody = document.getElementById('comentarios-body');
            const noComentarios = document.getElementById('no-comentarios');
            
            if (data.length === 0) {
                tbody.innerHTML = '';
                noComentarios.classList.remove('d-none');
                noComentarios.textContent = 'No se encontraron incidencias con los filtros seleccionados.';
                return;
            }

            noComentarios.classList.add('d-none');
            tbody.innerHTML = data.map(comentario => {
                return `
                <tr>
                    <td>${comentario.incidencia.id}</td>
                    <td>${comentario.incidencia.titulo || 'Sin título'}</td>
                    <td>${comentario.comentario ? comentario.comentario.substring(0, 50) + (comentario.comentario.length > 50 ? '...' : '') : 'Sin comentario'}</td>
                    <td>
                        ${comentario.incidencia.imagen 
                            ? `<img src="/img${comentario.incidencia.imagen}" alt="Imagen" width="50">` 
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
                        <div class="d-flex gap-2">
                            <button onclick="mostrarFormularioResolucion(${comentario.incidencia.id})" 
                                    class="btn btn-primary btn-sm"
                                    ${comentario.incidencia.estado !== 'en proceso' ? 'disabled' : ''}>
                                Resolver
                            </button>
                            <a href="#" class="btn btn-info btn-sm" title="Comunicar con usuario">
                                <i class="fas fa-comments"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `}).join('');
        })
        .catch(error => {
            console.error('Error en la petición:', error);
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

function aplicarFiltros() {
    const filtros = {
        estado: document.getElementById('filtroEstado').value,
        fecha: document.getElementById('filtroFecha').value
    };
    cargarComentarios(filtros);
}

function limpiarFiltros() {
    document.getElementById('filtroEstado').value = '';
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filtroFecha').value = today;
    aplicarFiltros(); // Usar aplicarFiltros en lugar de cargarComentarios
}
