<?php
session_start();

require_once __DIR__ . '/config/db.config.php';
require_once __DIR__ . '/controller/login_controller.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $loginController = new LoginController($conexion);
    $resultado = $loginController->login($usuario, $contrasena);

    if ($resultado !== true) {
        $mensaje = $resultado;
    }
}

// Incluye la vista
require_once __DIR__ . '/views/login.php';
?>