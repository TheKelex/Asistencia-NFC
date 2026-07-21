<?php
// conexion.php
// Conexión a la base de datos MySQL usando mysqli.

$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$baseDeDatos = 'sistema_asistencia_nfc';

$conexion = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

if ($conexion->connect_errno) {
    // No exponer información sensible de la base de datos.
    die('Error de conexión. Intente nuevamente más tarde.');
}

// Establecer conjunto de caracteres UTF-8 para evitar problemas de codificación.
$conexion->set_charset('utf8mb4');
