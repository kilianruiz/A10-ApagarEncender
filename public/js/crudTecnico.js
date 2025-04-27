document.addEventListener('DOMContentLoaded', function () {
    cargarComentarios('pendientes');

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filtroFecha')?.setAttribute('min', today);
    document.getElementById('filtroFecha')?.setAttribute('value', today);

    document.getElementById('filtroEstado')?.addEventListener('change', aplicarFiltros);
    document.getElementById('filtroFecha')?.addEventListener('change', aplicarFiltros);

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            let tipo = 'pendientes';
            switch (e.target.id) {
                case 'pendientes-tab':
                    tipo = 'pendientes';
                    break;
                case 'en-proceso-tab':
                    tipo = 'en_proceso';
                    break;
                case 'resueltas-tab':
                    tipo = 'resueltas';
                    break;
                case 'cerradas-tab':
                    tipo = 'cerradas';
                    break;
            }
            cargarComentarios(tipo);
        });
    });
});

function cargarComentarios(tipo = 'pendientes', filtros = {}) {
    const url = new URL('/tecnicos/comentarios', window.location.origin);
    const params = new URLSearchParams();
    params.append('tipo', tipo);

    if (filtros.estado) {
        params.append('estado', filtros.estado);
    }
    if (filtros.fecha) {
        params.append('fecha', filtros.fecha);
    }

    url.search = params.toString();

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Error en la petición');
            return response.json();
        })
        .then(data => {
            renderizarTabla(data, tipo);
        })
        .catch(error => {
            console.error('Error al cargar:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar las incidencias'
            });
        });
}

function renderizarTabla(datos, tipo) {
    let tbodyId = tipo.replace('_', '-') + '-body';
    const tbody = document.getElementById(tbodyId);
    const noData = document.getElementById('no-' + tipo.replace('_', '-'));

    if (!tbody || !noData) return;

    if (datos.length === 0) {
        tbody.innerHTML = '';
        noData.classList.remove('d-none');
        return;
    }

    noData.classList.add('d-none');

    tbody.innerHTML = datos.map(comentario => {
        const incidencia = comentario.incidencia;

        if (tipo === 'resueltas' || tipo === 'cerradas') {
            // Para resueltas y cerradas (mostrar feedback y sin acciones)
            return `
                <tr>
                    <td>${incidencia.id}</td>
                    <td>${incidencia.titulo || 'Sin título'}</td>
                    <td>${comentario.comentario ? comentario.comentario.substring(0, 50) + (comentario.comentario.length > 50 ? '...' : '') : 'Sin comentario'}</td>
                    <td>${incidencia.imagen ? `<img src="/img${incidencia.imagen}" width="50" alt="Imagen">` : 'Sin imagen'}</td>
                    <td>${new Date(comentario.created_at).toLocaleString('es')}</td>
                    <td>${incidencia.feedback || 'Sin feedback'}</td>
                </tr>
            `;
        } else {
            // Para pendientes y en proceso
            return `
                <tr>
                    <td>${incidencia.id}</td>
                    <td>${incidencia.titulo || 'Sin título'}</td>
                    <td>${comentario.comentario ? comentario.comentario.substring(0, 50) + (comentario.comentario.length > 50 ? '...' : '') : 'Sin comentario'}</td>
                    <td>${incidencia.imagen ? `<img src="/img${incidencia.imagen}" width="50" alt="Imagen">` : 'Sin imagen'}</td>
                    <td>${new Date(comentario.created_at).toLocaleString('es')}</td>
                    <td>
                        ${tipo === 'en_proceso' 
                            ? incidencia.estado   // Solo texto si es en_proceso
                            : `<select class="form-select form-select-sm" onchange="cambiarEstado(${incidencia.id}, this.value)">
                                <option value="asignada" ${incidencia.estado === 'asignada' ? 'selected' : ''}>Asignada</option>
                                <option value="en proceso" ${incidencia.estado === 'en proceso' ? 'selected' : ''}>En proceso</option>
                              </select>`
                        }
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="mostrarFormularioResolucion(${incidencia.id})"
                            ${incidencia.estado !== 'en proceso' ? 'disabled' : ''}>
                            Resolver
                        </button>
                    </td>
                </tr>
            `;
        }
    }).join('');
}

function aplicarFiltros() {
    const filtros = {
        estado: document.getElementById('filtroEstado')?.value,
        fecha: document.getElementById('filtroFecha')?.value
    };
    cargarComentarios('pendientes', filtros);
}

function mostrarFormularioResolucion(incidenciaId) {
    Swal.fire({
        title: 'Resolver Incidencia',
        html: `<textarea id="feedback" class="form-control" placeholder="Ingrese el feedback" rows="4"></textarea>`,
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
    }).then(result => {
        if (result.isConfirmed) {
            resolverIncidencia(incidenciaId, result.value);
        }
    });
}

function resolverIncidencia(incidenciaId, feedback) {
    const data = { incidencia_id: incidenciaId, feedback };

    fetch('/tecnicos/resolver-incidencia', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Resuelto!',
                text: data.message
            }).then(() => {
                recargarTodo();
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

function cambiarEstado(incidenciaId, nuevoEstado) {
    fetch('/tecnicos/cambiar-estado', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ incidencia_id: incidenciaId, estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: 'Estado actualizado correctamente',
                timer: 1200,
                showConfirmButton: false
            });
            recargarTodo();
        } else {
            throw new Error(data.message);
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

function recargarTodo() {
    cargarComentarios('pendientes');
    cargarComentarios('en_proceso');
    cargarComentarios('resueltas');
    cargarComentarios('cerradas');
}