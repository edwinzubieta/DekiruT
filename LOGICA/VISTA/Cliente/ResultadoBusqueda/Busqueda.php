<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda de Viajes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="resultados-viajes.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#2D4EFF', // Un azul vibrante como color primario
                        'primary-hover': '#1E3BCC',
                        'secondary': '#F3F4F6', // Un gris claro para fondos
                        'accent': '#10B981', // Un verde para acentos o éxito
                        'danger': '#EF4444', // Un rojo para errores
                        'card-bg': '#FFFFFF', // Fondo para las tarjetas de viaje
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-secondary font-sans">

    <div class="results-container container mx-auto px-4 py-8 md:py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Viajes Disponibles</h2>
            <a href="../Compraticket/Compraticket.php" class="text-primary hover:text-primary-hover font-medium transition duration-150 ease-in-out">
                &larr; Nueva Búsqueda
            </a>
        </div>

        <div id="loading-message" class="text-center py-10">
            <p class="text-xl text-gray-600">Buscando viajes...</p>
            </div>

        <div id="trip-results-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            </div>

        <p id="no-results-message" class="text-center text-xl text-gray-500 py-10" style="display:none;">
            No se encontraron viajes para tu búsqueda. Intenta con otros criterios.
        </p>

        <p id="error-api-message" class="text-center text-xl text-danger py-10" style="display:none;">
            Hubo un problema al cargar los viajes. Por favor, intenta de nuevo más tarde.
        </p>
    </div>

    <template id="trip-item-template">
        <div class="trip-item bg-card-bg rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 ease-in-out hover:shadow-2xl">
            <div class="p-6 flex-grow">
                <h3 class="text-xl font-semibold text-primary mb-2">
                    <span class="origen"></span> &rarr; <span class="destino"></span>
                </h3>
                <p class="text-gray-700 mb-1"><strong class="font-medium">Fecha:</strong> <span class="fecha"></span></p>
                <p class="text-gray-700 mb-1"><strong class="font-medium">Hora:</strong> <span class="hora"></span></p>
                <p class="text-gray-700 mb-1"><strong class="font-medium">Bus:</strong> <span class="numero_bus"></span></p>
                <p class="text-gray-600 mb-3"><strong class="font-medium">Asientos Disponibles:</strong> <span class="disponibles font-semibold text-accent"></span></p>
            </div>
            <div class="bg-gray-50 p-6 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-2xl font-bold text-primary mb-3 sm:mb-0">
                    $<span class="precio"></span> <span class="text-sm font-normal text-gray-500">COP</span>
                </p>
                <button class="select-trip-button bg-accent hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-50 transition duration-150 ease-in-out w-full sm:w-auto">
                    Seleccionar Viaje
                </button>
            </div>
        </div>
    </template>

    <script src="resultados-viajes.js"></script>
</body>
</html>
