<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo "No autorizado";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_viaje = $_POST['id_viaje'];
$cantidad = $_POST['cantidad'];
$total = $_POST['total'];

$nombre_pasajero = $_POST['nombre_pasajero'];
$documento_pasajero = $_POST['documento_pasajero'];
$email_pasajero = $_POST['email_pasajero'];
$telefono_pasajero = $_POST['telefono_pasajero'];

$conexion = new mysqli("localhost", "root", "", "DekiruTDB");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

try {
    $conexion->begin_transaction();

    // Obtener datos actuales del usuario y viaje
    $sql1 = "SELECT saldo_disponible FROM USUARIO WHERE id_usuario = ? FOR UPDATE";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("i", $id_usuario);
    $stmt1->execute();
    $resultado1 = $stmt1->get_result();
    $saldo = $resultado1->fetch_assoc()['saldo_disponible'];

    $sql2 = "SELECT asientos_disponibles FROM VIAJE WHERE id_viaje = ? FOR UPDATE";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("i", $id_viaje);
    $stmt2->execute();
    $resultado2 = $stmt2->get_result();
    $asientos = $resultado2->fetch_assoc()['asientos_disponibles'];

    if ($asientos < $cantidad) {
        throw new Exception("No hay suficientes asientos disponibles.");
    }

    if ($saldo < $total) {
        throw new Exception("Saldo insuficiente.");
    }

    // Restar asientos
    $sql3 = "UPDATE VIAJE SET asientos_disponibles = asientos_disponibles - ? WHERE id_viaje = ?";
    $stmt3 = $conexion->prepare($sql3);
    $stmt3->bind_param("ii", $cantidad, $id_viaje);
    $stmt3->execute();

    // Restar saldo
    $sql4 = "UPDATE USUARIO SET saldo_disponible = saldo_disponible - ? WHERE id_usuario = ?";
    $stmt4 = $conexion->prepare($sql4);
    $stmt4->bind_param("di", $total, $id_usuario);
    $stmt4->execute();

    // Insertar en FACTURA incluyendo los datos del pasajero
    $sql5 = "INSERT INTO FACTURA (
        id_usuario, id_viaje, cantidad_tiquetes, total_pagado, fecha,
        nombre_pasajero, documento_pasajero, email_pasajero, telefono_pasajero
    ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
    $stmt5 = $conexion->prepare($sql5);
    $stmt5->bind_param(
        "iiidssss",
        $id_usuario,
        $id_viaje,
        $cantidad,
        $total,
        $nombre_pasajero,
        $documento_pasajero,
        $email_pasajero,
        $telefono_pasajero
    );
    $stmt5->execute();

    $conexion->commit();

    echo "Compra realizada con éxito";

} catch (Exception $e) {
    $conexion->rollback();
    http_response_code(400);
    echo "Error en la transacción: " . $e->getMessage();
} finally {
    $conexion->close();
}
