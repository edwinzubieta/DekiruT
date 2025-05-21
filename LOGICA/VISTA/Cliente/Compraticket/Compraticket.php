<?php
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra de Tiquetes</title>
    <link rel="stylesheet" href="estilos_compraticket.css">
</head>


<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="../../../../IMAGENES/Logodekiru.jpg" alt="Logo Dekiru ERP" class="logo-erp rounded" style="height:50px;">
            <img src="../../../../IMAGENES/LogoRapidosDelAltiplano.jpg" alt="Logo Empresa de Transportes" class="logo-transporte rounded" style="height:50px;">
        </div>
        <div class="header-right">
            <div class="user-info-container" style="display:flex; align-items:center; gap:0.5rem; color:#374151;">
                <span>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></span>
                <span style="font-size:0.875rem; color:#6B7280;">| Saldo:</span>
                <span style="font-weight:600; color:#10B981;">$<?php echo htmlspecialchars($saldoFormateado); ?> COP</span>
            </div>
            <a href="../Historial/Historial.php" class="history-button" style="background-color:#E5E7EB; color:#1F2937; padding:0.5rem 1rem; border-radius:0.375rem; font-weight:500; text-decoration:none; transition: background-color 0.2s ease-in-out;">
                Historial de Compras
            </a>
            <a href="../../../MODELO/CerrarSesion.php" class="logout-button" style="background-color:#EF4444; color:white; padding:0.5rem 1rem; border-radius:0.375rem; font-weight:500; text-decoration:none; transition: background-color 0.2s ease-in-out;">
                Cerrar Sesión
            </a>
        </div>
    </div>
</header>

<style>
.logout-button:hover {
    background-color: #DC2626;
}
.history-button:hover {
    background-color: #D1D5DB;
}
</style>
<body>
    <div class="form-container">
    <h2>Buscar Viajes</h2>

    <form action="../ResultadoBusqueda/Busqueda.php" method="GET">
        <label for="origen">Ciudad Origen:</label>
        <select name="origen" id="origen" required>
            <option value="" disabled selected>Selecciona origen</option>
            <option value="Tunja">Tunja</option>
            <option value="Bogotá">Bogotá</option>
            <option value="Sogamoso">Sogamoso</option>
        </select>

        <label for="destino">Ciudad Destino:</label>
        <select name="destino" id="destino" required>
            <option value="" disabled selected>Selecciona destino</option>
            <option value="Tunja">Tunja</option>
            <option value="Bogotá">Bogotá</option>
            <option value="Sogamoso">Sogamoso</option>
        </select>

        <label for="fecha_viaje">Fecha del Viaje:</label>
        <input type="date" name="fecha_viaje" id="fecha_viaje" required min="<?php echo date('Y-m-d'); ?>">

        <button type="submit">Buscar</button>
        <!-- BOTÓN PARA VOLVER AL INICIO -->
        <div class="mt-10 text-center">
            <a href="../Main/Main.php" class="button">
                Volver al Inicio
            </a>
        </div>
    </form>

</div>

</body>
</html>
