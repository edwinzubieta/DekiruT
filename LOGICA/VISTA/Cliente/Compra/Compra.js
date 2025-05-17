document.addEventListener('DOMContentLoaded', () => {
    const tripSummaryContent = document.getElementById('trip-summary-content');
    const seatSummaryContent = document.getElementById('seat-summary-content');
    const finalTotalPriceElement = document.getElementById('final-total-price');
    const passengerDataForm = document.getElementById('passenger-data-form');
    const passengerFormErrorMessageDiv = document.getElementById('passenger-form-error-message');
    const generalErrorMessageDiv = document.getElementById('general-error-message');

    let purchaseData = {}; // Para almacenar todos los datos de la compra

    /**
     * Obtiene los datos de la selección de la URL o localStorage.
     * Esta función es una SIMULACIÓN. Deberás adaptarla a cómo realmente
     * pasas los datos desde la pantalla de selección de asientos.
     */
    function getSelectionData() {
        const params = new URLSearchParams(window.location.search);

        // Validar que el número de asientos coincida con el número de pasajeros
        if (mockData.selectedSeats.length !== mockData.numeroPasajeros) {
            console.warn("Advertencia: El número de asientos seleccionados no coincide con el número de pasajeros. Ajustando número de pasajeros.");
            // Podrías mostrar un error o ajustar el número de pasajeros
            // Por ahora, usaremos la cantidad de asientos seleccionados como el número de pasajeros para el cálculo del total.
            // mockData.numeroPasajeros = mockData.selectedSeats.length; // Opcional: ajustar
             generalErrorMessageDiv.innerHTML = `<div class="form-error-message">Error: La cantidad de asientos (${mockData.selectedSeats.length}) no coincide con el número de pasajeros indicado en la búsqueda (${mockData.numeroPasajeros}). Por favor, vuelve y corrige tu selección.</div>`;
            // Deshabilitar el formulario de pago si hay este error
            passengerDataForm.querySelector('button[type="submit"]').disabled = true;
            passengerDataForm.querySelector('button[type="submit"]').classList.add('opacity-50', 'cursor-not-allowed');

        }


        // Calcular el precio total
        mockData.totalPrice = mockData.precioPorAsiento * mockData.selectedSeats.length;
        purchaseData = { ...mockData }; // Guardar para uso posterior
        return mockData;
    }

    /**
     * Formatea una fecha YYYY-MM-DD a un formato más legible.
     */
    function formatearFecha(fechaString) {
        if (!fechaString) return 'N/A';
        const [year, month, day] = fechaString.split('-');
        const date = new Date(year, month - 1, day); // El mes es 0-indexed
        return date.toLocaleDateString('es-CO', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    /**
     * Muestra el resumen del viaje y los asientos.
     */
    function displaySummary() {
        const data = getSelectionData();

        if (!data.tripId || generalErrorMessageDiv.innerHTML !== '') { // Si hubo error en getSelectionData
            tripSummaryContent.innerHTML = '<p class="text-danger">No se pudieron cargar los detalles del viaje. Por favor, intenta de nuevo.</p>';
            seatSummaryContent.innerHTML = '<p class="text-danger">No se pudieron cargar los detalles de los asientos.</p>';
            return;
        }

        // Poblar detalles del viaje
        tripSummaryContent.innerHTML = `
            <div class="summary-item"><strong>Origen:</strong> <span>${data.origen}</span></div>
            <div class="summary-item"><strong>Destino:</strong> <span>${data.destino}</span></div>
            <div class="summary-item"><strong>Fecha:</strong> <span>${formatearFecha(data.fecha)}</span></div>
            <div class="summary-item"><strong>Hora:</strong> <span>${data.hora}</span></div>
            <div class="summary-item"><strong>Bus:</strong> <span>${data.bus || 'N/A'}</span></div>
            <div class="summary-item"><strong>Pasajeros:</strong> <span>${data.numeroPasajeros}</span></div>
        `;

        // Poblar detalles de asientos
        const seatsHtml = data.selectedSeats.map(seat => `<li class="seat-tag">${seat}</li>`).join('');
        seatSummaryContent.innerHTML = `
            <p><strong>Asientos:</strong></p>
            <ul class="seat-list mt-2 mb-3">${seatsHtml || 'Ninguno seleccionado'}</ul>
            <p><strong>Precio por asiento:</strong> $${data.precioPorAsiento.toLocaleString('es-CO')} COP</p>
        `;

        // Mostrar precio total
        finalTotalPriceElement.textContent = data.totalPrice.toLocaleString('es-CO');

        // (Opcional) Pre-rellenar datos del pasajero si está logueado (simulación)
        // if (usuarioLogueado) {
        // document.getElementById('nombre_pasajero').value = usuarioLogueado.nombre;
        // document.getElementById('documento_pasajero').value = usuarioLogueado.documento;
        // }
    }

    /**
     * Muestra un mensaje de error en el formulario de pasajeros.
     */
    function mostrarErrorFormularioPasajero(message) {
        passengerFormErrorMessageDiv.innerHTML = `<div class="form-error-message">${message}</div>`;
    }

    /**
     * Limpia los mensajes de error del formulario de pasajeros.
     */
    function limpiarErrorFormularioPasajero() {
        passengerFormErrorMessageDiv.innerHTML = '';
    }


    passengerDataForm.addEventListener('submit', (event) => {
        event.preventDefault();
        limpiarErrorFormularioPasajero();

        const nombrePasajero = document.getElementById('nombre_pasajero').value.trim();
        const documentoPasajero = document.getElementById('documento_pasajero').value.trim();
        const emailPasajero = document.getElementById('email_pasajero').value.trim();
        const telefonoPasajero = document.getElementById('telefono_pasajero').value.trim();

        // Validaciones
        if (!nombrePasajero) {
            mostrarErrorFormularioPasajero("El nombre completo es requerido.");
            document.getElementById('nombre_pasajero').focus();
            return;
        }
        if (!documentoPasajero) {
            mostrarErrorFormularioPasajero("El documento de identidad es requerido.");
            document.getElementById('documento_pasajero').focus();
            return;
        }
        // Validación simple de correo (opcional)
        if (emailPasajero && !/^\S+@\S+\.\S+$/.test(emailPasajero)) {
            mostrarErrorFormularioPasajero("Por favor, ingresa un correo electrónico válido.");
            document.getElementById('email_pasajero').focus();
            return;
        }

        // Recolectar datos del pasajero
        purchaseData.passengerInfo = {
            nombre: nombrePasajero,
            documento: documentoPasajero,
            email: emailPasajero,
            telefono: telefonoPasajero,
        };

        console.log("Datos completos de la compra:", purchaseData);

        // Aquí guardarías toda la información (purchaseData)
        // Por ejemplo, en localStorage para pasarla a la pasarela de pago,
        // o enviarías una parte al backend para crear una pre-orden.
        localStorage.setItem('pendingPurchase', JSON.stringify(purchaseData));

        // Simular redirección a la pasarela de pago
        generalErrorMessageDiv.innerHTML = `<div class="p-3 bg-blue-100 text-blue-700 rounded-md">Redirigiendo a la pasarela de pago...</div>`;
        setTimeout(() => {
            // La URL de la pasarela de pago sería algo como:
            // window.location.href = `/pasarela-pago.html`;
            alert("Simulación: Redirigiendo a la pasarela de pago.\n\nDatos guardados:\n" + JSON.stringify(purchaseData, null, 2));
            generalErrorMessageDiv.innerHTML = ''; // Limpiar mensaje
        }, 2000);
    });

    // Cargar y mostrar el resumen al iniciar la página
    displaySummary();
});
