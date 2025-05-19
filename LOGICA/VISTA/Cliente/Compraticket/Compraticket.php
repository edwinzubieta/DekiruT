<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca tu Próximo Viaje</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="Compraticket.css">
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-secondary flex items-center justify-center min-h-screen font-sans">

    <div class="search-container bg-white p-8 md:p-12 rounded-xl shadow-2xl w-full max-w-2xl">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-8">Busca tu Próximo Viaje</h1>

        <form id="search-trip-form" class="space-y-6">
            <div>
                <label for="origen" class="block text-sm font-medium text-gray-700 mb-1">Origen:</label>
                <select id="origen" name="origen" required
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                    <option value="" disabled selected>Selecciona una ciudad de origen</option>
                    <option value="Tunja">Tunja</option>
                    <option value="Bogota">Bogotá</option>
                    <option value="Sogamoso">Sogamoso</option>
                </select>
            </div>

            <div>
                <label for="destino" class="block text-sm font-medium text-gray-700 mb-1">Destino:</label>
                <select id="destino" name="destino" required
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                    <option value="" disabled selected>Selecciona una ciudad de destino</option>
                    <option value="Tunja">Tunja</option>
                    <option value="Bogota">Bogotá</option>
                    <option value="Sogamoso">Sogamoso</option>
                    </select>
            </div>

            <div>
                <label for="fecha-salida" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Salida:</label>
                <input type="date" id="fecha-salida" name="fecha_salida" required
                       class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
            </div>

        <!--<div> 
                <label for="hora-salida" class="block text-sm font-medium text-gray-700 mb-1">Hora de salida:</label>
                <select id="hora-salida" name="hora-salida" required
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                    <option value="" disabled selected>Selecciona una hora de salida</option>
                    <option value="Tunja">6 AM</option>
                    <option value="Bogota">12 PM</option>
                    <option value="Sogamoso">6 PM</option>
                    </select>
            </div>-->


             <button type="submit"
                class="w-full bg-primary hover:bg-primary-hover text-white font-semibold p-4 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition duration-150 ease-in-out text-lg">
                Buscar Viajes
            </button>
        </form>

        <div id="search-error-message" class="mt-6 text-center text-sm">
            </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

