<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "DekiruTDB");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener la última factura del usuario con los datos del viaje
$sql = "
    SELECT F.*, V.ciudad_origen, V.ciudad_destino, 
           V.fecha_salida, V.hora_salida, V.precio_tiquete
    FROM FACTURA F
    JOIN VIAJE V ON F.id_viaje = V.id_viaje
    WHERE F.id_usuario = ?
    ORDER BY F.fecha DESC
    LIMIT 1
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "No se encontró ninguna factura.";
    exit();
}

$factura = $resultado->fetch_assoc();



// ENVIAR CORREO ELECTRONICO CON FACTURA

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../../../vendor/PHPMailer/phpmailer/src/SMTP.php';
require '../../../../vendor/PHPMailer/phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Cambia esto si usas otro proveedor
    $mail->SMTPAuth = true;
    $mail->Username = 'dekiruerp@gmail.com';
    $mail->Password = 'ptac khgk qdaa taby';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Destinatarios
    $mail->setFrom('dekiruerp@gmail.com', 'Dekiru Travel');
    $mail->addAddress($factura['email_pasajero'], $factura['nombre_pasajero']);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Factura de tu compra - Dekiru Travel';
    
    $mensaje = '
        <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #2c3e50;">Factura de Compra</h2>

            <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">

            <h3 style="color: #007BFF;">🚌 Datos del Viaje</h3>
            <p><strong>Origen:</strong> ' . $factura['ciudad_origen'] . '</p>
            <p><strong>Destino:</strong> ' . $factura['ciudad_destino'] . '</p>
            <p><strong>Fecha de salida:</strong> ' . $factura['fecha_salida'] . '</p>
            <p><strong>Hora de salida:</strong> ' . $factura['hora_salida'] . '</p>
            <p><strong>Precio unitario:</strong> $' . number_format($factura['precio_tiquete'], 2) . '</p>
            <p><strong>Cantidad de tiquetes:</strong> ' . $factura['cantidad_tiquetes'] . '</p>
            <p><strong>Total pagado:</strong> <span style="color: #28a745; font-weight: bold;">$' . number_format($factura['total_pagado'], 2) . '</span></p>
            <p><strong>Fecha de compra:</strong> ' . $factura['fecha'] . '</p>

            <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">

            <h3 style="color: #007BFF;">👤 Datos del Pasajero</h3>
            <p><strong>Nombre:</strong> ' . $factura['nombre_pasajero'] . '</p>
            <p><strong>Documento:</strong> ' . $factura['documento_pasajero'] . '</p>
            <p><strong>Email:</strong> ' . $factura['email_pasajero'] . '</p>
            <p><strong>Teléfono:</strong> ' . $factura['telefono_pasajero'] . '</p>

            <hr style="border: 0; border-top: 1px solid #ccc; margin: 30px 0;">

            <p style="text-align: center; font-size: 13px; color: #888;">
            Gracias por confiar en <strong>Dekiru Travel</strong>. ¡Te deseamos un feliz viaje!<br><br>
            <a href="https://tusitio.com" style="color: #007BFF; text-decoration: none;">Visita nuestro sitio web</a>
            </p>
        </div>
        </div>
    ';


    $mail->Body = $mensaje;

    $mail->send();
    // Opcional: mensaje en consola
    // echo 'Correo enviado correctamente.';
} catch (Exception $e) {
    error_log("Error al enviar correo: {$mail->ErrorInfo}");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura - Dekiru Travel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }
        .factura {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .detalle {
            margin: 20px 0;
        }
        .detalle p {
            margin: 8px 0;
        }
        .volver {
            text-align: center;
            margin-top: 30px;
        }
        .volver a {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
        }
        .volver a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="factura">
    <h2>Factura de Compra</h2>
    
    <div class="detalle">
        <h3>Datos del viaje</h3>
        <p><strong>Origen:</strong> <?php echo $factura['ciudad_origen']; ?></p>
        <p><strong>Destino:</strong> <?php echo $factura['ciudad_destino']; ?></p>
        <p><strong>Fecha de salida:</strong> <?php echo $factura['fecha_salida']; ?></p>
        <p><strong>Hora de salida:</strong> <?php echo $factura['hora_salida']; ?></p>
        <p><strong>Precio unitario:</strong> $<?php echo number_format($factura['precio_tiquete'], 2); ?></p>
        <p><strong>Cantidad de tiquetes:</strong> <?php echo $factura['cantidad_tiquetes']; ?></p>
        <p><strong>Total pagado:</strong> $<?php echo number_format($factura['total_pagado'], 2); ?></p>
        <p><strong>Fecha de compra:</strong> <?php echo $factura['fecha']; ?></p>
    </div>

    <div class="detalle">
        <h3>Datos del pasajero</h3>
        <p><strong>Nombre:</strong> <?php echo $factura['nombre_pasajero']; ?></p>
        <p><strong>Documento:</strong> <?php echo $factura['documento_pasajero']; ?></p>
        <p><strong>Email:</strong> <?php echo $factura['email_pasajero']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $factura['telefono_pasajero']; ?></p>
    </div>
    
    <div class="volver">
        <a href="../Compraticket/Compraticket.php">Realizar otra compra</a>
    </div>
</div>

</body>
</html>
