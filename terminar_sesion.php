<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/conexion.php';

$response = [
    'success' => false,
    'mensaje' => 'No se pudo finalizar la sesión.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$idSesion = isset($input['id_sesion']) ? (int) $input['id_sesion'] : 0;

if ($idSesion <= 0) {
    $response['mensaje'] = 'Datos incompletos.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$sesionStmt = $conexion->prepare('SELECT id_sesion, estado FROM sesion_asistencia WHERE id_sesion = ? LIMIT 1');
$sesionStmt->bind_param('i', $idSesion);
$sesionStmt->execute();
$sesionResult = $sesionStmt->get_result();
$sesion = $sesionResult->fetch_assoc();
$sesionStmt->close();

if (!$sesion || $sesion['estado'] !== 'ACTIVA') {
    $response['mensaje'] = 'La sesión ya no está activa.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conexion->close();
    exit;
}

$fechaFin = date('Y-m-d H:i:s');
$updateStmt = $conexion->prepare('UPDATE sesion_asistencia SET estado = ?, fecha_fin = ? WHERE id_sesion = ?');
$estadoCerrada = 'CERRADA';
$updateStmt->bind_param('ssi', $estadoCerrada, $fechaFin, $idSesion);

if ($updateStmt->execute()) {
    $response['success'] = true;
    $response['mensaje'] = 'Sesión finalizada correctamente.';
} else {
    $response['mensaje'] = 'No se pudo cerrar la sesión.';
}

$updateStmt->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
$conexion->close();
