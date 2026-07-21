<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/conexion.php';

$response = [
    'success' => false,
    'tipo' => 'error',
    'mensaje' => 'No se pudo registrar la asistencia.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$idSesion = isset($input['id_sesion']) ? (int) $input['id_sesion'] : 0;
$idAprendiz = isset($input['id_aprendiz']) ? (int) $input['id_aprendiz'] : 0;

if ($idSesion <= 0 || $idAprendiz <= 0) {
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
    $response['tipo'] = 'cerrada';
    $response['mensaje'] = 'La sesión ya no está activa.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conexion->close();
    exit;
}

$duplicadoStmt = $conexion->prepare('SELECT 1 FROM asistencia WHERE id_sesion = ? AND id_aprendiz = ? LIMIT 1');
$duplicadoStmt->bind_param('ii', $idSesion, $idAprendiz);
$duplicadoStmt->execute();
$duplicadoStmt->store_result();

if ($duplicadoStmt->num_rows > 0) {
    $response['tipo'] = 'duplicado';
    $response['mensaje'] = 'El estudiante ya registró asistencia.';
    $duplicadoStmt->close();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conexion->close();
    exit;
}
$duplicadoStmt->close();

$aprendizStmt = $conexion->prepare('SELECT id_aprendiz, nombre, numero_identidad FROM aprendiz WHERE id_aprendiz = ? LIMIT 1');
$aprendizStmt->bind_param('i', $idAprendiz);
$aprendizStmt->execute();
$aprendizResult = $aprendizStmt->get_result();
$aprendiz = $aprendizResult->fetch_assoc();
$aprendizStmt->close();

if (!$aprendiz) {
    $response['tipo'] = 'no_registrada';
    $response['mensaje'] = 'Tarjeta no registrada.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conexion->close();
    exit;
}

$fechaHora = date('Y-m-d H:i:s');
$estado = 'PRESENTE';
$metodo = 'NFC';
$esManual = 0;
$motivo = null;
$idInstructor = isset($_SESSION['id']) ? (int) $_SESSION['id'] : null;

$insertStmt = $conexion->prepare(
    'INSERT INTO asistencia (id_sesion, id_aprendiz, fecha_hora_registro, estado, metodo_registro, es_manual, motivo_manual, id_instructor_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
$insertStmt->bind_param('iisssiss', $idSesion, $idAprendiz, $fechaHora, $estado, $metodo, $esManual, $motivo, $idInstructor);

if ($insertStmt->execute()) {
    $response['success'] = true;
    $response['tipo'] = 'success';
    $response['mensaje'] = 'Asistencia registrada';
    $response['aprendiz'] = [
        'nombre' => $aprendiz['nombre'],
        'numero_identidad' => $aprendiz['numero_identidad']
    ];
} else {
    $response['mensaje'] = 'No se pudo completar el registro.';
}

$insertStmt->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
$conexion->close();
