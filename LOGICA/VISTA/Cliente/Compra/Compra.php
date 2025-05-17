<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Compra y Datos del Pasajero</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="Compra.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#2D4EFF',
                        'primary-hover': '#1E3BCC',
                        'secondary': '#F3F4F6',
                        'accent': '#10B981', // Verde para acciones positivas
                        'accent-hover': '#059669',
                        'danger': '#EF4444',
                        'card-bg': '#FFFFFF',
                        'info': '#3B82F6', // Azul para información
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-secondary font-sans">

    <div class="summary-container container mx-auto px-4 py-8 md:py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Resumen de tu Compra</h2>
            <a href="javascript:history.back()" class="text-primary hover:text-primary-hover font-medium transition duration-150 ease-in-out">
                &larr; Volver
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div id="trip-details-summary" class="bg-card-bg p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-primary mb-4 border-b pb-2">Detalles del Viaje</h3>
                    <div id="trip-summary-content" class="space-y-2 text-gray-700">
                        <p>Cargando detalles del viaje...</p>
                    </div>
                </div>

                <div id="seat-details-summary" class="bg-card-bg p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-primary mb-4 border-b pb-2">Asientos Seleccionados</h3>
                    <div id="seat-summary-content" class="space-y-2 text-gray-700">
                        <p>Cargando detalles de asientos...</p>
                    </div>
                    <p class="mt-4 text-xl font-semibold text-gray-800">Total a Pagar:
                        <span class="text-accent">$<span id="final-total-price">0</span> COP</span>
                    </p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-card-bg p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-primary mb-6">Datos del Pasajero Principal</h3>
                    <form id="passenger-data-form" class="space-y-5">
                        <div>
                            <label for="nombre_pasajero" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo:</label>
                            <input type="text" id="nombre_pasajero" name="nombre_pasajero" required
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="documento_pasajero" class="block text-sm font-medium text-gray-700 mb-1">Documento de Identidad:</label>
                            <input type="text" id="documento_pasajero" name="documento_pasajero" required
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="email_pasajero" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico (Opcional):</label>
                            <input type="email" id="email_pasajero" name="email_pasajero"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="telefono_pasajero" class="block text-sm font-medium text-gray-700 mb-1">Teléfono (Opcional):</label>
                            <input type="tel" id="telefono_pasajero" name="telefono_pasajero"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        </div>

                        <div id="passenger-form-error-message" class="text-sm text-danger">
                            </div>

                        <button type="submit" id="proceed-to-payment-button"
                                class="w-full bg-accent hover:bg-accent-hover text-white font-semibold p-4 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-50 transition duration-150 ease-in-out text-lg">
                            Proceder al Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div id="general-error-message" class="mt-6 text-center text-sm"></div>
    </div>

    <script src="Compra.js"></script>
</body>
</html>