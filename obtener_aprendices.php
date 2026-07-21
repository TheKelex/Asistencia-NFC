<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/conexion.php';

$response = [
    'success' => false,
    'mensaje' => 'No se pudo cargar la sesión activa.'
];

$sesionStmt = $conexion->prepare(
    'SELECT id_sesion, id_ficha, id_competencia, fecha_inicio, tolerancia_minutos, estado FROM sesion_asistencia WHERE estado = ? ORDER BY id_sesion DESC LIMIT 1'
);
$estadoActiva = 'ACTIVA';
$sesionStmt->bind_param('s', $estadoActiva);
$sesionStmt->execute();
$sesionResult = $sesionStmt->get_result();
$sesion = $sesionResult->fetch_assoc();
$sesionStmt->close();

if (!$sesion) {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conexion->close();
    exit;
}

$fichaStmt = $conexion->prepare('SELECT id_ficha, nombre_programa, jornada FROM ficha WHERE id_ficha = ? LIMIT 1');
$fichaStmt->bind_param('i', $sesion['id_ficha']);
$fichaStmt->execute();
$fichaResult = $fichaStmt->get_result();
$ficha = $fichaResult->fetch_assoc();
$fichaStmt->close();

$competenciaStmt = $conexion->prepare('SELECT id_competencia, nombre FROM competencia WHERE id_competencia = ? LIMIT 1');
$competenciaStmt->bind_param('i', $sesion['id_competencia']);
$competenciaStmt->execute();
$competenciaResult = $competenciaStmt->get_result();
$competencia = $competenciaResult->fetch_assoc();
$competenciaStmt->close();

$aprendicesStmt = $conexion->prepare(
    'SELECT id_aprendiz, nombre, numero_identidad, serial_nfc FROM aprendiz WHERE id_ficha = ? ORDER BY nombre ASC'
);
$aprendicesStmt->bind_param('i', $sesion['id_ficha']);
$aprendicesStmt->execute();
$aprendicesResult = $aprendicesStmt->get_result();

$aprendices = [];
while ($aprendiz = $aprendicesResult->fetch_assoc()) {
    $aprendices[] = [
        'id_aprendiz' => (int) $aprendiz['id_aprendiz'],
        'nombre' => $aprendiz['nombre'],
        'numero_identidad' => $aprendiz['numero_identidad'],
        'serial_nfc' => $aprendiz['serial_nfc']
    ];
}
$aprendicesStmt->close();

$registradosStmt = $conexion->prepare('SELECT id_aprendiz FROM asistencia WHERE id_sesion = ?');
$registradosStmt->bind_param('i', $sesion['id_sesion']);
$registradosStmt->execute();
$registradosResult = $registradosStmt->get_result();

$registrados = [];
while ($registro = $registradosResult->fetch_assoc()) {
    $registrados[] = (int) $registro['id_aprendiz'];
}
$registradosStmt->close();

$response = [
    'success' => true,
    'mensaje' => 'Sesión cargada correctamente.',
    'session' => [
        'id_sesion' => (int) $sesion['id_sesion'],
        'id_ficha' => (int) $sesion['id_ficha'],
        'id_competencia' => (int) $sesion['id_competencia'],
        'fecha_inicio' => $sesion['fecha_inicio'],
        'tolerancia_minutos' => (int) $sesion['tolerancia_minutos'],
        'estado' => $sesion['estado']
    ],
    'ficha' => [
        'id_ficha' => (int) $ficha['id_ficha'],
        'nombre_programa' => $ficha['nombre_programa'],
        'jornada' => $ficha['jornada']
    ],
    'competencia' => [
        'id_competencia' => (int) $competencia['id_competencia'],
        'nombre' => $competencia['nombre']
    ],
    'aprendices' => $aprendices,
    'registrados' => $registrados
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
$conexion->close();
