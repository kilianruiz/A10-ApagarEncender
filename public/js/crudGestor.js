document.addEventListener("DOMContentLoaded", function () {
    // Cargar incidencias por estado
    function cargarIncidencias(estado) {
        let estadoNormalizado = estado.replace(/\s/g, "_");
        console.log('Cargando incidencias para estado:', estadoNormalizado);

        fetch(`${window.location.origin}/api/incidencias?estado=${estadoNormalizado}`, {
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
                ? "<tr><td colspan='12'>No hay incidencias en este estado.</td></tr>"
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
                    <td>${incidencia.descripcion || ''}</td>
                    <td>${incidencia.comentario || ''}</td>
                    <td>${incidencia.estado}</td>
                    <td>${incidencia.prioridad || ''}</td>
                    <td>${incidencia.user ? incidencia.user.name : 'No asignado'}</td>
                    <td>${incidencia.categoria ? incidencia.categoria.nombre : 'Sin Categoría'}</td>
                    <td>${incidencia.subcategoria ? incidencia.subcategoria.nombre : 'Sin Subcategoría'}</td>
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

    // Abrir el modal
    const modal = document.getElementById("modal-asignar");
    const formAsignar = document.getElementById("form-asignar");
    const tecnicoSelect = document.getElementById("tecnico-select");
    const incidenciaIdInput = document.getElementById("incidencia-id");

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("btn-abrir-modal")) {
            let incidenciaId = event.target.getAttribute("data-id");
            incidenciaIdInput.value = incidenciaId;
            cargarTecnicos();
            modal.style.display = "flex";
            modal.classList.add("show");
            document.body.style.overflow = 'hidden';
        }
    });

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

    // Cargar técnicos en el select
    function cargarTecnicos() {
        fetch("/api/tecnicos")
            .then(response => response.json())
            .then(tecnicos => {
                tecnicoSelect.innerHTML = '<option value="">Seleccione un técnico</option>';
                tecnicos.forEach(tecnico => {
                    let option = document.createElement("option");
                    option.value = tecnico.id;
                    option.textContent = tecnico.name;
                    tecnicoSelect.appendChild(option);
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

    // Cargar las incidencias iniciales
    cargarIncidencias(document.querySelector(".nav-link.active").getAttribute("data-status"));

    // Cambiar de pestaña
    document.querySelectorAll(".nav-link").forEach(tab => {
        tab.addEventListener("shown.bs.tab", function (event) {
            cargarIncidencias(event.target.getAttribute("data-status"));
        });
    });
});