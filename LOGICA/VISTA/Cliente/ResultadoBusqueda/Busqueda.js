

document.addEventListener('DOMContentLoaded', () => {
    const tripResultsList = document.getElementById('trip-results-list');
    const noResultsMessage = document.getElementById('no-results-message');
    const errorApiMessage = document.getElementById('error-api-message');
    const loadingMessage = document.getElementById('loading-message');
    const tripItemTemplate = document.getElementById('trip-item-template');

    /**
     * Obtiene los parámetros de búsqueda de la URL.
     * @returns {URLSearchParams}
     */
    function getSearchParams() {
        return new URLSearchParams(window.location.search);
    }

    /**
     * Simula una llamada a la API del backend para obtener viajes.
     * @param {URLSearchParams} params - Parámetros de búsqueda.
     * @returns {Promise<Array<Object>>} - Promesa que resuelve a un array de objetos de viaje.
     */
    async function fetchTripsFromAPI(params) {
        console.log("Simulando llamada a API con parámetros:", params.toString());
        // Simulación de retardo de red
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Datos de ejemplo (simulando respuesta de la API)
        // En un caso real, aquí harías: const response = await fetch(`/api/viajes?${params.toString()}`);
        // y luego: const data = await response.json();

        const origenParam = params.get('origen');
        const destinoParam = params.get('destino');
        const fechaSalidaParam = params.get('fecha_salida');

        // Mock de datos de viajes
        const mockViajes = [
            { id_viaje: 'V001', origen: 'Bogota', destino: 'Tunja', fecha_salida: '2025-07-15', hora_salida: '08:00', precio: 35000, numero_bus: 'B101', numero_sillas_disponibles: 15 },
            { id_viaje: 'V002', origen: 'Bogota', destino: 'Tunja', fecha_salida: '2025-07-15', hora_salida: '10:30', precio: 32000, numero_bus: 'B102', numero_sillas_disponibles: 5 },
            { id_viaje: 'V003', origen: 'Tunja', destino: 'Sogamoso', fecha_salida: '2025-07-16', hora_salida: '14:00', precio: 15000, numero_bus: 'T201', numero_sillas_disponibles: 20 },
            { id_viaje: 'V004', origen: 'Sogamoso', destino: 'Bogota', fecha_salida: '2025-07-17', hora_salida: '09:15', precio: 40000, numero_bus: 'S301', numero_sillas_disponibles: 12 },
            { id_viaje: 'V005', origen: 'Bogota', destino: 'Duitama', fecha_salida: '2025-07-15', hora_salida: '11:00', precio: 38000, numero_bus: 'B105', numero_sillas_disponibles: 8 },
            { id_viaje: 'V006', origen: 'Tunja', destino: 'Bogota', fecha_salida: '2025-07-16', hora_salida: '16:30', precio: 35000, numero_bus: 'T205', numero_sillas_disponibles: 22 },
        ];

        // Filtrar mockViajes basado en los parámetros (simulación básica)
        let filteredViajes = mockViajes.filter(viaje => {
            let matches = true;
            if (origenParam && viaje.origen.toLowerCase() !== origenParam.toLowerCase()) {
                matches = false;
            }
            if (destinoParam && viaje.destino.toLowerCase() !== destinoParam.toLowerCase()) {
                matches = false;
            }
            if (fechaSalidaParam && viaje.fecha_salida !== fechaSalidaParam) {
                matches = false;
            }
            return matches;
        });

        // Simular un error de API aleatoriamente para pruebas
        // if (Math.random() < 0.2) { // 20% de probabilidad de error
        //     throw new Error("Error simulado de la API");
        // }

        return filteredViajes;
    }

    /**
     * Renderiza los resultados de los viajes en la página.
     * @param {Array<Object>} viajes - Array de objetos de viaje.
     */
    function renderizarResultados(viajes) {
        tripResultsList.innerHTML = ''; // Limpiar resultados anteriores

        if (!viajes || viajes.length === 0) {
            noResultsMessage.style.display = 'block';
            return;
        }

        noResultsMessage.style.display = 'none';

        viajes.forEach(viaje => {
            const tripItemNode = tripItemTemplate.content.cloneNode(true); // Clonar la plantilla

            // Rellenar los datos del viaje
            tripItemNode.querySelector('.origen').textContent = viaje.origen;
            tripItemNode.querySelector('.destino').textContent = viaje.destino;
            tripItemNode.querySelector('.fecha').textContent = formatearFecha(viaje.fecha_salida);
            tripItemNode.querySelector('.hora').textContent = viaje.hora_salida;
            tripItemNode.querySelector('.precio').textContent = viaje.precio.toLocaleString('es-CO'); // Formato de moneda COP
            tripItemNode.querySelector('.numero_bus').textContent = viaje.numero_bus || 'N/A';
            tripItemNode.querySelector('.disponibles').textContent = viaje.numero_sillas_disponibles;

            const selectButton = tripItemNode.querySelector('.select-trip-button');
            selectButton.dataset.tripId = viaje.id_viaje; // Guardar el ID del viaje en el botón

            // Añadir event listener al botón "Seleccionar Viaje"
            selectButton.addEventListener('click', () => {
                seleccionarViaje(viaje);
            });

            tripResultsList.appendChild(tripItemNode);
        });
    }

    /**
     * Formatea una fecha YYYY-MM-DD a un formato más legible.
     * @param {string} fechaString - Fecha en formato YYYY-MM-DD.
     * @returns {string} - Fecha formateada (ej: "15 de julio de 2025").
     */
    function formatearFecha(fechaString) {
        if (!fechaString) return 'N/A';
        const [year, month, day] = fechaString.split('-');
        const date = new Date(year, month - 1, day);
        return date.toLocaleDateString('es-CO', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }


    /**
     * Maneja la selección de un viaje.
     * @param {Object} viajeData - Datos del viaje seleccionado.
     */
    function seleccionarViaje(viajeData) {
        console.log("Viaje seleccionado:", viajeData);
        // Guardar información del viaje (ej. en localStorage o pasar por URL)
        // localStorage.setItem('selectedTrip', JSON.stringify(viajeData));

        // Construir parámetros para la siguiente pantalla (Selección de Asientos)
        const queryParams = new URLSearchParams();
        queryParams.append('tripId', viajeData.id_viaje);
        queryParams.append('origen', viajeData.origen);
        queryParams.append('destino', viajeData.destino);
        queryParams.append('fecha', viajeData.fecha_salida);
        queryParams.append('hora', viajeData.hora_salida);
        queryParams.append('precio', viajeData.precio);
        queryParams.append('bus', viajeData.numero_bus);
        // Obtener el número de pasajeros de la búsqueda original (si es necesario)
        const searchParamsOriginal = getSearchParams();
        if (searchParamsOriginal.has('pasajeros')) {
            queryParams.append('pasajeros', searchParamsOriginal.get('pasajeros'));
        }


        // Redirigir a la pantalla de selección de asientos (simulación)
        const urlSeleccionAsientos = `/seleccion-asientos.html?${queryParams.toString()}`;
        console.log("Redirigiendo a selección de asientos:", urlSeleccionAsientos);

        alert(`Simulación: Redirigiendo a la selección de asientos para el viaje ${viajeData.id_viaje}.\nURL: ${urlSeleccionAsientos}\n\nEn una aplicación real, no usarías alert.`);
        // window.location.href = urlSeleccionAsientos; // Descomentar para redirigir realmente
    }

    /**
     * Función principal para cargar y mostrar los viajes.
     */
    async function cargarViajes() {
        loadingMessage.style.display = 'block';
        noResultsMessage.style.display = 'none';
        errorApiMessage.style.display = 'none';
        tripResultsList.innerHTML = ''; // Limpiar antes de cargar

        const searchParams = getSearchParams();

        // Mostrar los parámetros de búsqueda en la consola (para depuración)
        console.log("Parámetros de búsqueda recibidos en resultados:");
        for (const [key, value] of searchParams.entries()) {
            console.log(`${key}: ${value}`);
        }


        try {
            const viajes = await fetchTripsFromAPI(searchParams);
            renderizarResultados(viajes);
        } catch (error) {
            console.error("Error al obtener los viajes:", error);
            errorApiMessage.style.display = 'block';
        } finally {
            loadingMessage.style.display = 'none';
        }
    }

    // Iniciar la carga de viajes al cargar la página
    cargarViajes();
});
