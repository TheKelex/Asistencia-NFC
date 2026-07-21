<?php
// crear_sesion.php
// Procesa el formulario de creación de sesión de asistencia.

session_start();


require_once '../conexion.php';

// Validar método POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Solicitud no válida.';
    exit;
}

// Recibir y sanitizar los datos.
$idFicha = isset($_POST['id_ficha']) ? trim($_POST['id_ficha']) : '';
$idCompetencia = isset($_POST['id_competencia']) ? trim($_POST['id_competencia']) : '';
$tolerancia = isset($_POST['tolerancia']) ? trim($_POST['tolerancia']) : '';

// Validar campos obligatorios.
if ($idFicha === '' || $idCompetencia === '' || $tolerancia === '') {
    echo 'Todos los campos son obligatorios. Por favor completa la información requerida.';
    exit;
}

// Validar valores numéricos básicos.
if (!ctype_digit($idFicha) || !ctype_digit($idCompetencia) || !ctype_digit($tolerancia)) {
    echo 'Los valores enviados no son válidos.';
    exit;
}

$idFicha = (int) $idFicha;
$idCompetencia = (int) $idCompetencia;
$tolerancia = (int) $tolerancia;

// Verificar que la ficha exista.
$queryFicha = $conexion->prepare('SELECT 1 FROM ficha WHERE id_ficha = ? LIMIT 1');
$queryFicha->bind_param('i', $idFicha);
$queryFicha->execute();
$queryFicha->store_result();

if ($queryFicha->num_rows === 0) {
    echo 'La ficha seleccionada no existe.';
    $queryFicha->close();
    $conexion->close();
    exit;
}
$queryFicha->close();

// Verificar que la competencia exista.
$queryCompetencia = $conexion->prepare('SELECT 1 FROM competencia WHERE id_competencia = ? LIMIT 1');
$queryCompetencia->bind_param('i', $idCompetencia);
$queryCompetencia->execute();
$queryCompetencia->store_result();

if ($queryCompetencia->num_rows === 0) {
    echo 'La competencia seleccionada no existe.';
    $queryCompetencia->close();
    $conexion->close();
    exit;
}
$queryCompetencia->close();

// Insertar la nueva sesión de asistencia.
$queryInsert = $conexion->prepare(
    'INSERT INTO sesion_asistencia (id_ficha, id_competencia, fecha_inicio, tolerancia_minutos, estado) VALUES (?, ?, ?, ?, ?)'
);

$fechaInicio = date('Y-m-d H:i:s');
$estado = 'ACTIVA';

$queryInsert->bind_param('iisis', $idFicha, $idCompetencia, $fechaInicio, $tolerancia, $estado);

if ($queryInsert->execute()) {
    $queryInsert->close();
    $conexion->close();
    // Redirigir a la página de sesión activa.
    header('Location: Sesion_Activa/Sesion.php');
    exit;
}

// Si ocurre un error, mostrar un mensaje genérico.
echo 'No se pudo crear la sesión. Intente nuevamente más tarde.';
$queryInsert->close();
$conexion->close();
