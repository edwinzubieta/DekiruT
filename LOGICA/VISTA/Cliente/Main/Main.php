<?php
session_start();

// Verificar si el usuario está logueado, si no, redirigir a login
// Es una buena práctica tener esto, aunque no estaba en tu código original.
// Si no lo necesitas, puedes comentarlo o eliminarlo.
if (!isset($_SESSION['id_usuario'])) {
    // Ajusta la ruta a tu página de login si es diferente
    header("Location: ../../MODELO/Login/Login_UI.php"); // Ejemplo de ruta, ajústala
    exit();
}


$current_page = basename($_SERVER['PHP_SELF']);

// Recuperar datos del usuario de la sesión
$nombreUsuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
$saldoUsuario = $_SESSION['saldo_usuario'] ?? 0.00; // Obtener saldo de la sesión

// Formatear el saldo como moneda COP
$saldoFormateado = number_format($saldoUsuario, 2, ',', '.');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Sistema de Tiquetes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Main.css">
    <style>

        .logout-button, .history-button { /* Estilo base para los botones del header */
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem; /* py-2 px-4 */
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 500; /* font-medium */
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
        }
        .logout-button {
            background-color: #EF4444; /* bg-red-500 */
            color: white;
        }
        .logout-button:hover {
            background-color: #DC2626; /* hover:bg-red-600 */
        }
        .history-button {
            background-color: #E5E7EB; /* bg-gray-200 */
            color: #1F2937; /* text-gray-800 */
        }
        .history-button:hover {
            background-color: #D1D5DB; /* hover:bg-gray-300 */
        }
        .user-info-container {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* space-x-2 */
            color: #374151; /* text-gray-700 */
        }
        .user-info-container .saldo-text {
            font-size: 0.875rem; /* text-sm */
            color: #6B7280; /* text-gray-500 */
        }
        .user-info-container .saldo-amount {
            font-weight: 600; /* font-semibold */
            color: #10B981; /* text-green-600, un color para el saldo */
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-100 font-inter">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="..\..\..\..\IMAGENES\Logodekiru.jpg" alt="Logo Dekiru ERP" class="logo-erp rounded">
                <img src="..\..\..\..\IMAGENES\LogoRapidosDelAltiplano.jpg" alt="Logo Empresa de Transportes" class="logo-transporte rounded">
            </div>
            <div class="flex items-center space-x-4">
                <div class="user-info-container">
                    <span>
                        Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>
                    </span>
                    <span class="saldo-text">| Saldo:</span>
                    <span class="saldo-amount">$<?php echo htmlspecialchars($saldoFormateado); ?> COP</span>
                </div>
                <a href="historial_compras.php" class="history-button">
                    Historial de Compras
                </a>
                <a href="../../../MODELO/CerrarSesion.php" class="logout-button">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <section class="hero-section text-white py-20 md:py-32">
            <div class="container mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Tu Próximo Destino te Espera</h1>
                <p class="text-lg md:text-xl mb-10 max-w-2xl mx-auto">Encuentra y compra tus tiquetes de autobús de forma fácil, rápida y segura. Explora nuevas rutas y planifica tu aventura con nosotros.</p>
                
                <a href="../Compraticket/Compraticket.php" id="search-trips-cta-link" class="cta-button bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-xl shadow-lg inline-block">
                    Buscar Viajes Ahora
                </a>
            </div>
        </section>

        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">¿Por qué Viajar con Nosotros?</h2>
                <div class="grid md:grid-cols-3 gap-8 text-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <svg class="w-12 h-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Seguridad Garantizada</h3>
                        <p class="text-gray-600">Viaja con la tranquilidad de que tu seguridad es nuestra prioridad.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <svg class="w-12 h-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Puntualidad</h3>
                        <p class="text-gray-600">Cumplimos con nuestros horarios para que llegues a tiempo a tu destino.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <svg class="w-12 h-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Comodidad a Bordo</h3>
                        <p class="text-gray-600">Disfruta de un viaje confortable con nuestros modernos autobuses.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <div class="mb-4">
                <a href="#about" class="px-3 hover:text-blue-400">Sobre Nosotros</a>
                <a href="#contact" class="px-3 hover:text-blue-400">Contacto</a>
                <a href="#terms" class="px-3 hover:text-blue-400">Términos y Condiciones</a>
                <a href="#privacy" class="px-3 hover:text-blue-400">Política de Privacidad</a>
            </div>
            <p>&copy; <span id="current-year"></span> Sistema de Tiquetes Rapidos del Altipalno. Todos los derechos reservados.</p>
            <p class="text-sm text-gray-400 mt-1">Desarrollado en colaboración con Dekiru ERP</p>
        </div>
    </footer>

    <script src="Main.js"></script>
    <script>
        // Script para el año actual en el footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>

</body>
</html>
