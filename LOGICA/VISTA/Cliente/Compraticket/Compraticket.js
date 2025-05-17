
document.addEventListener('DOMContentLoaded', () => {
    const searchTripForm = document.getElementById('search-trip-form');
    const origenSelect = document.getElementById('origen');
    const destinoSelect = document.getElementById('destino');
    const fechaSalidaInput = document.getElementById('fecha-salida');
    const fechaRegresoInput = document.getElementById('fecha-regreso');
    const pasajerosInput = document.getElementById('pasajeros');
    const searchErrorMessageDiv = document.getElementById('search-error-message');

    // Opciones de ciudades (podrían venir de una API en el futuro)
    const ciudades = ["Tunja", "Bogota", "Sogamoso", "Duitama", "Paipa", "Villa de Leyva"]; // Ejemplo ampliado

    /**
     * Popula las opciones de un select.
     * @param {HTMLSelectElement} selectElement - El elemento select a popular.
     * @param {string[]} optionsArray - Array de strings con las opciones.
     * @param {string} [disabledOptionValue] - El valor de la opción a deshabilitar.
     */
    function popularSelect(selectElement, optionsArray, disabledOptionValue = null) {
        // Guardar la opción "Selecciona..."
        const placeholderOption = selectElement.querySelector('option[value=""][disabled]');
        selectElement.innerHTML = ''; // Limpiar opciones existentes
        if (placeholderOption) {
            selectElement.appendChild(placeholderOption); // Volver a añadir la opción "Selecciona..."
        }


        optionsArray.forEach(ciudad => {
            const option = document.createElement('option');
            option.value = ciudad;
            option.textContent = ciudad;
            if (ciudad === disabledOptionValue) {
                option.disabled = true;
                option.classList.add('text-gray-400', 'italic'); // Estilo para la opción deshabilitada
            }
            selectElement.appendChild(option);
        });
    }

    // Popular selects iniciales (ejemplo, podrías tenerlos ya en el HTML o cargarlos de otra forma)
    // Si las opciones ya están en el HTML, esta parte se puede simplificar o quitar.
    // popularSelect(origenSelect, ciudades);
    // popularSelect(destinoSelect, ciudades);


    /**
     * Muestra un mensaje de error.
     * @param {string} message - El mensaje a mostrar.
     */
    function mostrarError(message) {
        searchErrorMessageDiv.innerHTML = `<div class="error-message">${message}</div>`;
    }

    /**
     * Limpia los mensajes de error.
     */
    function limpiarErrores() {
        searchErrorMessageDiv.innerHTML = '';
    }

    /**
     * Actualiza las opciones del selector de destino para que no se pueda seleccionar el mismo origen.
     */
    function actualizarOpcionesDestino() {
        const origenSeleccionado = origenSelect.value;
        const destinoSeleccionadoActual = destinoSelect.value; // Guardar el valor actual

        // Obtener todas las opciones de ciudades del select de origen (o de la lista `ciudades`)
        const opcionesDisponibles = Array.from(origenSelect.options)
                                         .filter(opt => opt.value !== "") // Excluir el placeholder
                                         .map(opt => opt.value);

        popularSelect(destinoSelect, opcionesDisponibles, origenSeleccionado);

        // Intentar restaurar la selección previa si aún es válida
        if (destinoSeleccionadoActual && destinoSeleccionadoActual !== origenSeleccionado) {
            destinoSelect.value = destinoSeleccionadoActual;
        } else if (destinoSeleccionadoActual === origenSeleccionado) {
            destinoSelect.value = ""; // Resetear si el destino era igual al nuevo origen
        }
         // Asegurar que la opción placeholder "Selecciona una ciudad de destino" esté visible si no hay valor
        if (!destinoSelect.value) {
            const placeholder = destinoSelect.querySelector('option[value=""][disabled]');
            if (placeholder) placeholder.selected = true;
        }
    }

    // Event listener para el cambio en el selector de origen
    origenSelect.addEventListener('change', actualizarOpcionesDestino);

    // Llamada inicial para configurar el selector de destino si hay un origen preseleccionado (aunque no es el caso aquí)
    actualizarOpcionesDestino();


    searchTripForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevenir el envío por defecto del formulario
        limpiarErrores(); // Limpiar errores previos

        // Recolectar datos del formulario
        const origen = origenSelect.value;
        const destino = destinoSelect.value;
        const fechaSalida = fechaSalidaInput.value;
        const fechaRegreso = fechaRegresoInput.value;
        const pasajeros = parseInt(pasajerosInput.value, 10);

        // --- Validaciones ---
        if (!origen) {
            mostrarError("Por favor, selecciona una ciudad de origen.");
            origenSelect.focus();
            return;
        }

        if (!destino) {
            mostrarError("Por favor, selecciona una ciudad de destino.");
            destinoSelect.focus();
            return;
        }

        if (origen === destino) {
            mostrarError("La ciudad de origen y destino no pueden ser la misma.");
            destinoSelect.focus();
            return;
        }

        if (!fechaSalida) {
            mostrarError("Por favor, selecciona una fecha de salida.");
            fechaSalidaInput.focus();
            return;
        }

        const hoy = new Date();
        const fechaSalidaDate = new Date(fechaSalida + "T00:00:00"); //Asegurar que se compara solo la fecha

        // Ajustar 'hoy' para que solo contenga la fecha, sin la hora, para una comparación correcta
        hoy.setHours(0, 0, 0, 0);

        if (fechaSalidaDate < hoy) {
            mostrarError("La fecha de salida no puede ser anterior a la fecha actual.");
            fechaSalidaInput.focus();
            return;
        }

        if (fechaRegreso) {
            const fechaRegresoDate = new Date(fechaRegreso + "T00:00:00");
            if (fechaRegresoDate < fechaSalidaDate) {
                mostrarError("La fecha de regreso no puede ser anterior a la fecha de salida.");
                fechaRegresoInput.focus();
                return;
            }
        }

        if (isNaN(pasajeros) || pasajeros < 1) {
            mostrarError("El número de pasajeros debe ser al menos 1.");
            pasajerosInput.focus();
            return;
        }

        // Si todas las validaciones son correctas
        console.log("Formulario válido. Datos a enviar:");
        console.log({ origen, destino, fechaSalida, fechaRegreso, pasajeros });

        // Construir los parámetros de consulta para la API
        const queryParams = new URLSearchParams();
        queryParams.append('origen', origen);
        queryParams.append('destino', destino);
        queryParams.append('fecha_salida', fechaSalida);
        if (fechaRegreso) {
            queryParams.append('fecha_regreso', fechaRegreso);
        }
        queryParams.append('pasajeros', pasajeros);

        const urlResultados = `../ResultadoBusqueda/Busqueda.php?${queryParams.toString()}`;
        console.log("Redirigiendo a:", urlResultados);

        // Mostrar un mensaje de éxito simulado y luego redirigir
        searchErrorMessageDiv.innerHTML = `<div class="success-message">Búsqueda exitosa. Redirigiendo...</div>`;

        setTimeout(() => {
            // window.location.href = urlResultados; // Descomentar para redirigir realmente
            alert(`Simulación: Redirigiendo a ${urlResultados}\n\nEn una aplicación real, no usarías alert.`);
            // Aquí, en lugar del alert, harías la redirección:
            // window.location.href = urlResultados;
            // O, si estás en un entorno de componentes (React, Vue, Angular),
            // usarías el sistema de enrutamiento del framework.
        }, 1500); // Esperar un poco para que el usuario vea el mensaje

    });

    // Establecer la fecha mínima para los inputs de fecha
    const todayString = new Date().toISOString().split('T')[0];
    fechaSalidaInput.setAttribute('min', todayString);
    fechaRegresoInput.setAttribute('min', todayString); 
});
