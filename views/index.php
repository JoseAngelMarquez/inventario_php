<?php
require_once __DIR__ . "/../config/db.config.php";
require_once __DIR__ . '/../controller/login_controller.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $loginController = new LoginController($conexion);
    $mensaje = $loginController->login($usuario, $contrasena);
}

require_once __DIR__ . '/login.php';
?>
