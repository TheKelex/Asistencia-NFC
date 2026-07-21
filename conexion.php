<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sistema_asistencia_nfc";

// Crea la conexion
$conexion = new mysqli($host, $user, $pass, $dbname);

// Verifica que la conexion no falle
if ($conexion->connect_error) {

    die("Error de conexión: " . $conexion->connect_error);

}

?>