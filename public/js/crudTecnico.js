document.addEventListener('DOMContentLoaded', function() {
    cargarComentarios('pendientes');

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            let tipo = 'pendientes'; // Default
            switch (e.target.id) {
                case 'pendientes-tab':
                    tipo = 'pendientes';
                    break;
                case 'en-proceso-tab':
                    tipo = 'en_proceso'; // <- AQUÍ CORREGIDO (en_proceso)
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

function cargarComentarios(tipo = 'pendientes') {
    const url = new URL('/tecnicos/comentarios', window.location.origin);
    url.searchParams.append('tipo', tipo);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const bodyId = tipo.replace('_', '-');
            const tbody = document.getElementById(`${bodyId}-body`) || document.getElementById('comentarios-body');
            const noData = document.getElementById(`no-${bodyId}`) || document.getElementById('no-comentarios');

            if (!tbody) {
                console.error('No tbody encontrado para', tipo);
                return;
            }

            if (data.length === 0) {
                tbody.innerHTML = '';
                noData.classList.remove('d-none');
                return;
            }

            noData.classList.add('d-none');
            tbody.innerHTML = data.map(comentario => {
                let acciones = '';

                if (tipo === 'pendientes') {
                    acciones = `
                        <select class="form-select form-select-sm" onchange="cambiarEstado(${comentario.incidencia.id}, this.value)">
                            <option value="asignada" ${comentario.incidencia.estado === 'asignada' ? 'selected' : ''}>Asignada</option>
                            <option value="en proceso" ${comentario.incidencia.estado === 'en proceso' ? 'selected' : ''}>En Proceso</option>
                        </select>
                    `;
                } else if (tipo === 'en_proceso') {
                    acciones = `
                        <button class="btn btn-primary btn-sm" onclick="mostrarFormularioResolucion(${comentario.incidencia.id})"
                            ${comentario.incidencia.estado !== 'en proceso' ? 'disabled' : ''}>
                            Resolver
                        </button>
                    `;
                } else {
                    acciones = `<span class="text-muted">-</span>`;
                }

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
                        <td>${comentario.incidencia.estado}</td>
                        <td>${acciones}</td>
                    </tr>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Error al cargar incidencias:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar incidencias'
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
                title: '¡Estado actualizado!',
                text: 'La incidencia ha cambiado de estado.',
                timer: 1500,
                showConfirmButton: false
            });

            // ⚡ Recargar automáticamente la pestaña activa
            const activeTab = document.querySelector('.nav-link.active');
            let tipo = 'pendientes'; // Default

            if (activeTab) {
                switch (activeTab.id) {
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
            }

            cargarComentarios(tipo);
        } else {
            throw new Error(data.message || 'Error al actualizar estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al actualizar estado'
        });
    });
}

function mostrarFormularioResolucion(incidenciaId) {
    Swal.fire({
        title: 'Resolver Incidencia',
        html: `<textarea id="feedback" class="form-control" placeholder="Ingrese feedback" rows="4"></textarea>`,
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
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // 💥 Al resolver recargamos incidencias activas
                const activeTab = document.querySelector('.nav-link.active');
                let tipo = 'pendientes'; // default

                if (activeTab) {
                    switch (activeTab.id) {
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
                }

                cargarComentarios(tipo); // 🔥 Actualizamos la pestaña actual
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al resolver la incidencia'
        });
    });
}