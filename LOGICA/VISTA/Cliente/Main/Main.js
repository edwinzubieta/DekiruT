// Lógica de JavaScript para la página principal

        // Actualizar el año actual en el pie de página
        document.getElementById('current-year').textContent = new Date().getFullYear();

        // Manejador para el botón principal de búsqueda de viajes
        const searchTripsButton = document.getElementById('search-trips-cta');
        if (searchTripsButton) {
            searchTripsButton.addEventListener('click', function() {
                // Redirigir a la pantalla de búsqueda de viajes (item 4 de desarrollo_frontend_pasos)
                // En una aplicación real, esto sería una navegación a una ruta específica,
                // por ejemplo: window.location.href = '/buscar-viajes';
                // Por ahora, simularemos con un alert o log.
                console.log('Redirigiendo a la pantalla de búsqueda de viajes...');
                alert('Serás redirigido a la pantalla de búsqueda de viajes.');
                // Aquí deberías implementar la lógica de navegación a la siguiente pantalla.
                // Por ejemplo, si estás construyendo una SPA, usarías tu router.
                // Si son archivos HTML separados, sería: window.location.href = 'pantalla_busqueda_viajes.html';
            });
        }

        // Manejadores para los enlaces de Iniciar Sesión y Registrarse (simulados)
        const loginLink = document.getElementById('login-link');
        if (loginLink) {
            loginLink.addEventListener('click', function(event) {
                event.preventDefault(); // Prevenir la navegación por defecto del ancla
                alert('Redirigiendo a la pantalla de Iniciar Sesión...');
                // window.location.href = 'pantalla_login.html';
            });
        }

        const registerLink = document.getElementById('register-link');
        if (registerLink) {
            registerLink.addEventListener('click', function(event) {
                event.preventDefault(); // Prevenir la navegación por defecto del ancla
                alert('Redirigiendo a la pantalla de Registro...');
                // window.location.href = 'pantalla_registro.html';
            });
        }

        // Simulación de navegación para enlaces del footer
        document.querySelectorAll('footer a').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const section = this.getAttribute('href').substring(1);
                alert(`Navegando a la sección: ${section}`);
                // Aquí implementarías la lógica para ir a la página/sección correspondiente.
            });
        });