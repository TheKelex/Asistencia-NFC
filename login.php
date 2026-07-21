<?php

// Se inicia la sesion para guardar el usuario como variable de sesion
session_start();
include "./conexion.php";

$username = $_POST["username"];
$password = $_POST["password"];

// Query == SQL (consulta)
// El "?" es un placeholder (luego se da el valor)
$query = "SELECT * FROM instructor WHERE usuario = ?";

// Prepara la consulta
$stmt = $conexion->prepare($query);
// Se remplaza el "?" por el valor de $username ("s" significa que el dato es string)
$stmt->bind_param("s", $username);
// Ejecuta la consulta
$stmt->execute();

// Se guarda el resultado de la consulta
$resultado = $stmt->get_result();
// El resultado de la fila se guarda en un arreglo
$usuario = $resultado->fetch_assoc();

// Verificar que exista el usuario y la contraseña
if ($usuario && password_verify($password, $usuario["contrasena"])) {

    // Guardan el id y el usuario en variable de sesion
    $_SESSION["id"] = $usuario["id_instructor"];
    $_SESSION["usuario"] = $usuario["usuario"];

    // Lo saca
    header("Location: ./Dashboard/Dashboard.html");
    exit;

} else {

    header("Location: index.php?error=1");
    exit;

}

?>