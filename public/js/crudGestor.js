document.addEventListener("DOMContentLoaded", function () {
    let filtrosActuales = {};

    // Inicializar eventos de filtros
    const estados = ['sin_asignar', 'asignada', 'en_proceso', 'resuelta', 'cerrada'];
    estados.forEach(estado => {
        // Inicializar filtros para cada estado
        filtrosActuales[estado] = {
            titulo: '',
            prioridad: '',
            tecnico_id: ''
        };

        // Evento para filtro de título
        const filtroTitulo = document.getElementById(`filtroTitulo-${estado}`);
        if (filtroTitulo) {
            filtroTitulo.addEventListener('input', () => {
                filtrosActuales[estado].titulo = filtroTitulo.value;
                cargarIncidencias(estado);
            });
        }

        // Evento para filtro de prioridad
        const filtroPrioridad = document.getElementById(`filtroPrioridad-${estado}`);
        if (filtroPrioridad) {
            filtroPrioridad.addEventListener('change', () => {
                filtrosActuales[estado].prioridad = filtroPrioridad.value;
                cargarIncidencias(estado);
            });
        }

        // Evento para filtro de técnico (excepto en sin_asignar)
        if (estado !== 'sin_asignar') {
            const filtroTecnico = document.getElementById(`filtroTecnico-${estado}`);
            if (filtroTecnico) {
                filtroTecnico.addEventListener('change', () => {
                    filtrosActuales[estado].tecnico_id = filtroTecnico.value;
                    cargarIncidencias(estado);
                });
            }
        }
    });

    // Función para limpiar filtros
    window.limpiarFiltros = function(estado) {
        // Resetear valores de los filtros en la UI
        const filtroTitulo = document.getElementById(`filtroTitulo-${estado}`);
        const filtroPrioridad = document.getElementById(`filtroPrioridad-${estado}`);
        if (filtroTitulo) filtroTitulo.value = '';
        if (filtroPrioridad) filtroPrioridad.value = '';

        if (estado !== 'sin_asignar') {
            const filtroTecnico = document.getElementById(`filtroTecnico-${estado}`);
            if (filtroTecnico) filtroTecnico.value = '';
        }

        // Resetear filtros actuales
        filtrosActuales[estado] = {
            titulo: '',
            prioridad: '',
            tecnico_id: ''
        };

        // Recargar incidencias
        cargarIncidencias(estado);
    };

    // Función modificada para cargar incidencias con filtros
    function cargarIncidencias(estado) {
        console.log('Estado recibido:', estado);
        let estadoNormalizado = estado.replace(/\s/g, "_");
        console.log('Estado normalizado:', estadoNormalizado);

        // Construir URL con filtros
        let url = new URL(`${window.location.origin}/api/incidencias`);
        url.searchParams.append('estado', estadoNormalizado);

        // Añadir filtros activos
        const filtros = filtrosActuales[estadoNormalizado];
        if (filtros.titulo) url.searchParams.append('titulo', filtros.titulo);
        if (filtros.prioridad) url.searchParams.append('prioridad', filtros.prioridad);
        if (filtros.tecnico_id) url.searchParams.append('tecnico_id', filtros.tecnico_id);

        console.log('URL de la petición:', url.toString());

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Status de la respuesta:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            let tabla = document.getElementById(`tabla-${estadoNormalizado}`);
            if (!tabla) {
                console.error('No se encontró la tabla para el estado:', estadoNormalizado);
                return;
            }

            if (data.error) {
                console.error('Error del servidor:', data.error);
                tabla.innerHTML = `<tr><td colspan='12'>Error: ${data.error}</td></tr>`;
                return;
            }

            tabla.innerHTML = data.length === 0
                ? `<tr><td colspan='12'>No hay incidencias ${estadoNormalizado.replace(/_/g, ' ')}</td></tr>`
                : "";

            data.forEach(incidencia => {
                console.log('Procesando incidencia:', incidencia);
                let fila = document.createElement("tr");

                // Obtener el técnico asignado
                const tecnicoAsignado = incidencia.tecnico_asignado && incidencia.tecnico_asignado.length > 0 
                    ? incidencia.tecnico_asignado[0].name 
                    : 'Sin técnico asignado';

                fila.innerHTML = `
                    <td>${incidencia.id}</td>
                    <td>${incidencia.titulo}</td>
                    <td>${incidencia.descripcion}</td>
                    <td>${incidencia.comentario || ''}</td>
                    <td>${incidencia.estado}</td>
                    <td>${incidencia.prioridad || ''}</td>
                    <td>${incidencia.user}</td>
                    <td>${incidencia.categoria}</td>
                    <td>${incidencia.subcategoria}</td>
                    <td>${incidencia.feedback || ''}</td>
                    <td>${new Date(incidencia.created_at).toLocaleString('es')}</td>
                    <td class="text-center">
                        ${incidencia.estado === 'sin asignar' 
                            ? `<button class="btn-abrir-modal btn btn-primary" data-id="${incidencia.id}">Asignar</button>`
                            : `<span class="badge bg-info">${tecnicoAsignado}</span>`
                        }
                    </td>
                `;
                tabla.appendChild(fila);

                // Añadir evento al botón si es una incidencia sin asignar
                if (incidencia.estado === 'sin asignar') {
                    const btnAsignar = fila.querySelector('.btn-abrir-modal');
                    if (btnAsignar) {
                        btnAsignar.addEventListener('click', function() {
                            incidenciaIdInput.value = incidencia.id;
                            cargarTecnicos();
                            modal.style.display = "flex";
                            modal.classList.add("show");
                            document.body.style.overflow = 'hidden';
                        });
                    }
                }
            });
        })
        .catch(error => {
            console.error("Error al cargar incidencias:", error);
            let tabla = document.getElementById(`tabla-${estadoNormalizado}`);
            if (tabla) {
                tabla.innerHTML = `<tr><td colspan='12'>Error al cargar las incidencias: ${error.message}</td></tr>`;
            }
        });
    }

    // Cargar técnicos en el select
    function cargarTecnicos() {
        fetch("/api/tecnicos")
            .then(response => response.json())
            .then(tecnicos => {
                // Llenar el select del modal
                tecnicoSelect.innerHTML = '<option value="">Seleccione un técnico</option>';
                tecnicos.forEach(tecnico => {
                    let option = document.createElement("option");
                    option.value = tecnico.id;
                    option.textContent = tecnico.name;
                    tecnicoSelect.appendChild(option);
                });

                // Llenar los selects de filtros
                estados.forEach(estado => {
                    if (estado !== 'sin_asignar') {
                        const filtroTecnico = document.getElementById(`filtroTecnico-${estado}`);
                        if (filtroTecnico) {
                            filtroTecnico.innerHTML = '<option value="">Todos los técnicos</option>';
                            tecnicos.forEach(tecnico => {
                                let option = document.createElement("option");
                                option.value = tecnico.id;
                                option.textContent = tecnico.name;
                                filtroTecnico.appendChild(option);
                            });
                        }
                    }
                });
            })
            .catch(error => {
                console.error("Error al cargar técnicos:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los técnicos',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    // Inicialización del modal y sus elementos
    const modal = document.getElementById("modal-asignar");
    const formAsignar = document.getElementById("form-asignar");
    const tecnicoSelect = document.getElementById("tecnico-select");
    const incidenciaIdInput = document.getElementById("incidencia-id");

    // Cerrar el modal
    document.querySelector(".btn-cancelar").addEventListener("click", function () {
        cerrarModal();
    });

    // Función para cerrar el modal
    function cerrarModal() {
        modal.classList.remove("show");
        setTimeout(() => {
            modal.style.display = "none";
            document.body.style.overflow = 'auto';
        }, 200);
    }

    // Manejar el envío del formulario
    formAsignar.addEventListener("submit", function (event) {
        event.preventDefault();

        let incidenciaId = incidenciaIdInput.value;
        let tecnicoId = tecnicoSelect.value;

        if (!tecnicoId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor, seleccione un técnico antes de asignar',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        fetch("/api/asignar-incidencia", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                incidencia_id: incidenciaId,
                tecnico_id: tecnicoId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error,
                    confirmButtonColor: '#3085d6'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Incidencia asignada correctamente',
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    cerrarModal();
                    cargarIncidencias("sin_asignar");
                    cargarIncidencias("asignada");
                });
            }
        })
        .catch(error => {
            console.error("Error al asignar incidencia:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al asignar la incidencia',
                confirmButtonColor: '#3085d6'
            });
        });
    });

    // Cargar las incidencias iniciales y los técnicos
    cargarTecnicos();
    
    // Inicializar eventos de las pestañas
    document.querySelectorAll('#incidenciasTabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            const estado = this.getAttribute('data-status');
            console.log('Tab clicked, estado:', estado);
            cargarIncidencias(estado);
        });
    });

    // Cargar el estado inicial
    const tabActivo = document.querySelector('#incidenciasTabs .nav-link.active');
    if (tabActivo) {
        const estadoInicial = tabActivo.getAttribute('data-status');
        console.log('Cargando estado inicial:', estadoInicial);
        cargarIncidencias(estadoInicial);
    }
});