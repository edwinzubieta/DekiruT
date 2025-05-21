<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../MODELO/Login/Login_UI.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// Conexión
$conexion = new mysqli("localhost", "root", "", "DekiruTDB");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "
SELECT F.*, 
       V.fecha_salida, V.hora_salida, 
       V.ciudad_origen AS origen, 
       V.ciudad_destino AS destino
FROM FACTURA F
JOIN VIAJE V ON F.id_viaje = V.id_viaje
WHERE F.id_usuario = ?
ORDER BY F.fecha DESC
";


$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Viajes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Historial de Viajes</h1>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="grid gap-6">
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between text-gray-700 font-medium mb-2">
                            <span><?php echo htmlspecialchars($row['origen']); ?> → <?php echo htmlspecialchars($row['destino']); ?></span>
                            <span><?php echo date('d/m/Y H:i', strtotime($row['fecha_salida'] . ' ' . $row['hora_salida'])); ?></span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p><strong>Pasajero:</strong> <?php echo htmlspecialchars($row['nombre_pasajero']); ?></p>
                            <p><strong>Documento:</strong> <?php echo htmlspecialchars($row['documento_pasajero']); ?></p>
                            <p><strong>Tiquetes:</strong> <?php echo $row['cantidad_tiquetes']; ?></p>
                            <p><strong>Total Pagado:</strong> $<?php echo number_format($row['total_pagado'], 0, ',', '.'); ?> COP</p>
                            <p><strong>Fecha de Compra:</strong> <?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500 mt-10">No tienes viajes registrados.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conexion->close();
        ?>
    </div>

    <!-- BOTÓN PARA VOLVER AL INICIO -->
        <div class="mt-10 text-center">
            <a href="../Main/Main.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition-all shadow-md">
                Volver al Inicio
            </a>
        </div>
</body>
</html>
