<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "DekiruTDB";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("<p class='error'>Error de conexión: " . $conn->connect_error . "</p>");
}

$origen = isset($_GET['origen']) ? trim($_GET['origen']) : '';
$destino = isset($_GET['destino']) ? trim($_GET['destino']) : '';
$fecha = isset($_GET['fecha_viaje']) ? trim($_GET['fecha_viaje']) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de la Búsqueda</title>
    <link rel="stylesheet" href="estilos_resultado.css">
    <style>
        .viaje {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: background-color 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .viaje:hover {
            background-color: #f5f5f5;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .viaje p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <div>
            <img src="../../../../IMAGENES/Logodekiru.jpg" alt="Logo ERP" class="logo-erp">
            <img src="../../../../IMAGENES/LogoRapidosDelAltiplano.jpg" alt="Logo Empresa" class="logo-transporte">
        </div>
        <div class="user-info-container">
            <span>Resultados de búsqueda</span>
        </div>
    </div>
</header>

<div class="form-container">
    <h2>Resultados de la Búsqueda</h2>

    <?php
    if ($origen === '' || $destino === '' || $fecha === '') {
        echo "<p class='error'>Por favor ingrese origen, destino y fecha.</p>";
    } else {
        $sql = "SELECT * FROM VIAJE WHERE ciudad_origen = ? AND ciudad_destino = ? AND fecha_salida = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $origen, $destino, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<a href='../Compra/Compra.php?" . http_build_query([
                    'id_viaje' => $row['id_viaje'],
                    'origen' => $row['ciudad_origen'],
                    'destino' => $row['ciudad_destino'],
                    'fecha_salida' => $row['fecha_salida'],
                    'hora_salida' => $row['hora_salida'],
                    'precio_tiquete' => $row['precio_tiquete'],
                    'asientos_disponibles' => $row['asientos_disponibles']
                ]) . "' class='viaje'>";

                echo "<p><strong>Viaje ID:</strong> " . htmlspecialchars($row["id_viaje"]) . "</p>";
                echo "<p><strong>Origen:</strong> " . htmlspecialchars($row["ciudad_origen"]) . "</p>";
                echo "<p><strong>Destino:</strong> " . htmlspecialchars($row["ciudad_destino"]) . "</p>";
                echo "<p><strong>Fecha:</strong> " . htmlspecialchars($row["fecha_salida"]) . "</p>";
                echo "<p><strong>Hora:</strong> " . htmlspecialchars($row["hora_salida"]) . "</p>";
                echo "<p><strong>Precio:</strong> $" . number_format((float)$row["precio_tiquete"], 0, ',', '.') . "</p>";
                echo "<p><strong>Asientos Disponibles:</strong> " . htmlspecialchars($row["asientos_disponibles"]) . "</p>";
                echo "</a>";
            }

        } else {
            echo "<p class='no-result'>No se encontraron viajes para esa ruta y fecha.</p>";
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <div class="volver-container">
        <a href="../Compraticket/Compraticket.php" class="btn-volver">← Volver a buscar</a>
    </div>
</div>

</body>
</html>
