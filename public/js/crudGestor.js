document.addEventListener("DOMContentLoaded", function () {
    function cargarIncidencias(estado) {
        let estadoNormalizado = estado.replace(/\s/g, "_");
        let url = `/api/incidencias?estado=${estadoNormalizado}`;

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
                tabla.innerHTML = "";

                if (data.length === 0) {
                    tabla.innerHTML = "<tr><td colspan='6'>No hay incidencias en este estado.</td></tr>";
                } else {
                    data.forEach(incidencia => {
                        let fila = document.createElement("tr");
                        fila.innerHTML = `
                            <td>${incidencia.id}</td>
                            <td>${incidencia.titulo}</td>
                            <td>${incidencia.descripcion}</td>
                            <td>${incidencia.comentario}</td>
                            <td>${incidencia.estado}</td>
                            <td>${incidencia.prioridad}</td>
                            <td>${incidencia.user ? incidencia.user.name : 'No asignado'}</td>
                            <td>${incidencia.categoria ? incidencia.categoria.nombre : 'Sin Categoría'}</td>
                            <td>${incidencia.subcategoria ? incidencia.subcategoria.nombre : 'Sin Subcategoría'}</td>
                            <td>${incidencia.feedback}</td>
                            <td>${new Date(incidencia.created_at).toLocaleString('es')}</td>
                            <td>
                                <select class="asignar-tecnico" data-id="${incidencia.id}">
                                    <option value="">Seleccionar Técnico</option>
                                </select>
                                <button class="btn-asignar" data-id="${incidencia.id}">Asignar</button>
                            </td>
                        `;
                        tabla.appendChild(fila);
                        cargarTecnicos(fila.querySelector(".asignar-tecnico"));
                    });
                }
            })
            .catch(error => {
                console.error("Error al obtener incidencias:", error);
            });
    }

    function cargarTecnicos(selectElement) {
        fetch("/api/tecnicos")
            .then(response => {
                if (!response.ok) {
                    throw new Error("No se encontraron técnicos asignados");
                }
                return response.json();
            })
            .then(data => {
                console.log("Técnicos cargados:", data);
    
                if (Array.isArray(data)) {
                    data.forEach(tecnico => {
                        let option = document.createElement("option");
                        option.value = tecnico.id;
                        option.textContent = tecnico.name;
                        selectElement.appendChild(option);
                    });
                } else {
                    console.warn("Respuesta inesperada de la API", data);
                }
            })
            .catch(error => console.error("Error al cargar técnicos:", error));
    }    

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("btn-asignar")) {
            let incidenciaId = event.target.getAttribute("data-id");
            let select = event.target.previousElementSibling;
            let tecnicoId = select.value;

            if (!tecnicoId) {
                alert("Seleccione un técnico");
                return;
            }

            console.log("Asignando incidencia:", { incidencia_id: incidenciaId, tecnico_id: tecnicoId });

            fetch(`/api/asignar-incidencia`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ incidencia_id: incidenciaId, tecnico_id: tecnicoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("Error: " + data.error);
                } else {
                    alert("Incidencia asignada correctamente");
                    cargarIncidencias("sin asignar");
                }
            })
            .catch(error => console.error("Error al asignar incidencia:", error));
        }
    });

    document.querySelectorAll(".nav-link").forEach(tab => {
        tab.addEventListener("shown.bs.tab", function (event) {
            let estado = event.target.getAttribute("data-status");
            cargarIncidencias(estado);
        });
    });

    let estadoInicial = document.querySelector(".nav-link.active").getAttribute("data-status");
    cargarIncidencias(estadoInicial);
});