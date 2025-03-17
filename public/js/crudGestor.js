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
                // Obtener el técnico asignado (el primero en la lista de usuarios)
                const tecnicoAsignado = incidencia.usuarios && incidencia.usuarios.length > 0 
                    ? incidencia.usuarios[0] 
                    : null;

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
                    <td>
                        ${incidencia.estado === 'sin asignar' 
                            ? `<button class="btn-abrir-modal btn btn-primary" data-id="${incidencia.id}">Asignar</button>`
                            : `<span class="text-muted">Técnico: ${tecnicoAsignado ? tecnicoAsignado.name : 'Sin técnico'}</span>`
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
            incidenciaIdInput.value = incidenciaId; // Guardar el ID de la incidencia
            cargarTecnicos(); // Cargar técnicos en el select
            modal.style.display = "flex"; // Mostrar el modal
        }
    });

    // Cerrar el modal
    document.querySelector(".btn-cancelar").addEventListener("click", function () {
        modal.style.display = "none";
    });

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
            .catch(error => console.error("Error al cargar técnicos:", error));
    }

    // Manejar el envío del formulario
    formAsignar.addEventListener("submit", function (event) {
        event.preventDefault();

        let incidenciaId = incidenciaIdInput.value;
        let tecnicoId = tecnicoSelect.value;

        if (!tecnicoId) {
            alert("Seleccione un técnico antes de asignar.");
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
                alert("Error: " + data.error);
            } else {
                alert("Incidencia asignada correctamente");
                modal.style.display = "none"; // Cerrar el modal
                cargarIncidencias("sin_asignar");
                cargarIncidencias("asignada");
            }
        })
        .catch(error => console.error("Error al asignar incidencia:", error));
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