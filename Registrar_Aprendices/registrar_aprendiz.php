<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../conexion.php';

// Solo permite peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido.']);
    exit;
}

// Lee el body JSON enviado por fetch()
$data = json_decode(file_get_contents('php://input'), true);

$accion       = $data['accion']       ?? '';   // 'crear' | 'editar' | 'eliminar'
$nombre       = trim($data['nombre']  ?? '');
$apellido     = trim($data['apellido']?? '');
$numero_id    = trim($data['numero_identidad'] ?? '');
$id_ficha     = intval($data['id_ficha']       ?? 0);
$estado_nfc   = trim($data['estado_nfc']       ?? 'Pendiente');
$correo       = trim($data['correo']           ?? '');
$id_aprendiz  = intval($data['id_aprendiz']    ?? 0);

// ── CREAR ──────────────────────────────────────────────────────────────────
if ($accion === 'crear') {

    if (!$nombre || !$apellido || !$numero_id || !$id_ficha) {
        echo json_encode(['success' => false, 'mensaje' => 'Faltan campos obligatorios.']);
        exit;
    }

    $nombre_completo = $nombre . ' ' . $apellido;

    // Verificar duplicado por número de identidad
    $dup = $conexion->prepare('SELECT id_aprendiz FROM aprendiz WHERE numero_identidad = ?');
    $dup->bind_param('s', $numero_id);
    $dup->execute();
    $dup->store_result();
    if ($dup->num_rows > 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Ya existe un aprendiz con ese número de identificación.']);
        $dup->close();
        exit;
    }
    $dup->close();

    $stmt = $conexion->prepare(
        'INSERT INTO aprendiz (nombre, numero_identidad, id_ficha, estado_nfc, correo) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('ssisss', $nombre_completo, $numero_id, $id_ficha, $estado_nfc, $correo);

    // Algunas versiones de la tabla no tienen estado_nfc ni correo → manejo de error
    if (!$stmt) {
        // Intentar sin esos campos
        $stmt = $conexion->prepare(
            'INSERT INTO aprendiz (nombre, numero_identidad, id_ficha) VALUES (?, ?, ?)'
        );
        $stmt->bind_param('ssi', $nombre_completo, $numero_id, $id_ficha);
    }

    if ($stmt->execute()) {
        echo json_encode([
            'success'     => true,
            'mensaje'     => 'Aprendiz registrado correctamente.',
            'id_aprendiz' => $conexion->insert_id
        ]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al guardar: ' . $stmt->error]);
    }

    $stmt->close();

// ── EDITAR ─────────────────────────────────────────────────────────────────
} elseif ($accion === 'editar') {

    if (!$id_aprendiz || !$nombre || !$apellido || !$numero_id || !$id_ficha) {
        echo json_encode(['success' => false, 'mensaje' => 'Faltan campos obligatorios.']);
        exit;
    }

    $nombre_completo = $nombre . ' ' . $apellido;

    // Verificar duplicado excluyendo el mismo aprendiz
    $dup = $conexion->prepare('SELECT id_aprendiz FROM aprendiz WHERE numero_identidad = ? AND id_aprendiz != ?');
    $dup->bind_param('si', $numero_id, $id_aprendiz);
    $dup->execute();
    $dup->store_result();
    if ($dup->num_rows > 0) {
        echo json_encode(['success' => false, 'mensaje' => 'Ese número de identificación ya pertenece a otro aprendiz.']);
        $dup->close();
        exit;
    }
    $dup->close();

    $stmt = $conexion->prepare(
        'UPDATE aprendiz SET nombre = ?, numero_identidad = ?, id_ficha = ?, estado_nfc = ?, correo = ? WHERE id_aprendiz = ?'
    );
    $stmt->bind_param('ssissi', $nombre_completo, $numero_id, $id_ficha, $estado_nfc, $correo, $id_aprendiz);

    if (!$stmt->execute()) {
        // Fallback sin campos opcionales
        $stmt->close();
        $stmt = $conexion->prepare(
            'UPDATE aprendiz SET nombre = ?, numero_identidad = ?, id_ficha = ? WHERE id_aprendiz = ?'
        );
        $stmt->bind_param('ssii', $nombre_completo, $numero_id, $id_ficha, $id_aprendiz);
        $stmt->execute();
    }

    if ($stmt->affected_rows >= 0) {
        echo json_encode(['success' => true, 'mensaje' => 'Aprendiz actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar: ' . $stmt->error]);
    }

    $stmt->close();

// ── ELIMINAR ───────────────────────────────────────────────────────────────
} elseif ($accion === 'eliminar') {

    if (!$id_aprendiz) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de aprendiz no válido.']);
        exit;
    }

    $stmt = $conexion->prepare('DELETE FROM aprendiz WHERE id_aprendiz = ?');
    $stmt->bind_param('i', $id_aprendiz);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Aprendiz eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar: ' . $stmt->error]);
    }

    $stmt->close();

} else {
    echo json_encode(['success' => false, 'mensaje' => 'Acción desconocida.']);
}

$conexion->close();
