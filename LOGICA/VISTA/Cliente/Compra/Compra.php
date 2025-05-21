<!DOCTYPE html>
<?php
// ../Compra/Compra.php
$id_viaje = $_GET['id_viaje'] ?? '';
$origen = $_GET['origen'] ?? '';
$destino = $_GET['destino'] ?? '';
$fecha = $_GET['fecha_salida'] ?? '';
$hora = $_GET['hora_salida'] ?? '';
$precio = $_GET['precio_tiquete'] ?? '';
$asientos = $_GET['asientos_disponibles'] ?? '';
$precio_valido = is_numeric($precio) ? floatval($precio) : 0;

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../MODELO/Login/Login_UI.php");
    exit();
}

// Recuperar datos de sesión
$idUsuario = $_SESSION['id_usuario'];
$nombreUsuario = $_SESSION['nombre_usuario'] ?? 'Usuario';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "DekiruTDB");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consultar saldo desde la base de datos
$sql = "SELECT saldo_disponible FROM USUARIO WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $saldoUsuario = $fila['saldo_disponible'];
} else {
    $saldoUsuario = 0.00; // Si no se encuentra, asumimos 0 como respaldo
}

$stmt->close();
$conexion->close();

// Formatear el saldo como moneda COP
$saldoFormateado = number_format($saldoUsuario, 2, ',', '.');

?>

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

        const saldoUsuario = <?php echo json_encode(floatval($saldoUsuario)); ?>;
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
                <div id="trip-summary-content" class="space-y-3 text-gray-700 text-base leading-relaxed bg-card-bg p-6 rounded-xl shadow-lg">
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-medium text-gray-600">Origen:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($origen); ?></span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-medium text-gray-600">Destino:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($destino); ?></span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-medium text-gray-600">Fecha de Salida:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($fecha); ?></span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-medium text-gray-600">Hora de Salida:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($hora); ?></span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-medium text-gray-600">Precio del Tiquete:</span>
                        <span class="text-accent font-semibold">$<?php echo number_format($precio_valido, 0, ',', '.'); ?> COP</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Asientos Disponibles:</span>
                        <span class="text-gray-800"><?php echo htmlspecialchars($asientos); ?></span>
                    </div>
                </div>



                <div id="seat-details-summary" class="bg-card-bg p-6 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-semibold text-primary mb-4 border-b pb-2">Asientos Seleccionados</h3>

                    <div class="space-y-4 text-gray-700">
                        <div class="flex items-center justify-between">
                            <label for="cantidad_asientos" class="text-lg font-medium">Cantidad de Asientos:</label>
                            <input type="number" id="cantidad_asientos" name="cantidad_asientos"
                                min="1" max="<?php echo $asientos; ?>"
                                data-precio="<?php echo $precio_valido; ?>"
                                value="1"
                                class="w-24 p-2 border border-gray-300 rounded-md shadow-sm text-center focus:ring-primary focus:border-primary transition duration-150 ease-in-out">


                        </div>
                        <div class="text-sm text-gray-500">
                            Asientos disponibles: <strong><?php echo $asientos; ?></strong>
                        </div>
                    </div>

                    <p class="mt-6 text-xl font-semibold text-gray-800">
                        Total a Pagar:
                        <span class="text-accent">$<span id="final-total-price"><?php echo number_format($precio_valido, 0, ',', '.'); ?></span> COP</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const cantidadInput = document.getElementById('cantidad_asientos');
        const precioUnitario = parseFloat(cantidadInput.dataset.precio) || 0;
        const totalPriceSpan = document.getElementById('final-total-price');
        const errorMessageDiv = document.getElementById('general-error-message');
        const botonPago = document.getElementById('proceed-to-payment-button');
        const saldoUsuario = <?php echo json_encode(floatval($saldoUsuario)); ?>;

        function actualizarTotal() {
            let cantidad = parseInt(cantidadInput.value);
            if (isNaN(cantidad) || cantidad < 1) {
                cantidad = 1;
                cantidadInput.value = 1;
            }
            if (cantidad > parseInt(cantidadInput.max)) {
                cantidad = parseInt(cantidadInput.max);
                cantidadInput.value = cantidad;
            }

            const total = precioUnitario * cantidad;
            totalPriceSpan.textContent = total.toLocaleString('es-CO', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            });

            if (total > saldoUsuario) {
                errorMessageDiv.textContent = 'Saldo insuficiente para comprar ' + cantidad + ' tiquete(s). Tu saldo actual es $' + saldoUsuario.toLocaleString('es-CO', {minimumFractionDigits: 2});
                errorMessageDiv.classList.add('error-visible');
                botonPago.disabled = true;
                botonPago.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                errorMessageDiv.textContent = '';
                errorMessageDiv.classList.remove('error-visible');
                botonPago.disabled = false;
                botonPago.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        actualizarTotal();
        cantidadInput.addEventListener('input', actualizarTotal);

            // Form submit handler
            document.getElementById('passenger-data-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const cantidad = parseInt(cantidadInput.value) || 1;
                const total = cantidad * precioUnitario;
                const id_viaje = <?php echo json_encode($id_viaje); ?>;

                // Capturar datos del pasajero
                const nombre = document.getElementById('nombre_pasajero').value.trim();
                const documento = document.getElementById('documento_pasajero').value.trim();
                const email = document.getElementById('email_pasajero').value.trim();
                const telefono = document.getElementById('telefono_pasajero').value.trim();

                if (nombre === '' || documento === '') {
                    document.getElementById('passenger-form-error-message').textContent = 'Por favor completa los campos obligatorios.';
                    return;
                }

                document.getElementById('passenger-form-error-message').textContent = '';

                fetch('procesar_pago.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id_viaje: id_viaje,
                        cantidad: cantidad,
                        total: total,
                        nombre_pasajero: nombre,
                        documento_pasajero: documento,
                        email_pasajero: email,
                        telefono_pasajero: telefono
                    })
                })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    window.location.href = "FacturaUI.php";
                })
                .catch(err => {
                    alert("Error al procesar el pago: " + err);
                });
            });
        });
    </script>


</body>
</html>