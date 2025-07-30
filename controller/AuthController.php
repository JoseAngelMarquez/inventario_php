<?php
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../config/db.config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $usuarioModel = new Usuario($conexion);
    $usuario = $usuarioModel->verificarUsuario($usuario, $contrasena);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario['usuario'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            header("Location: ../views/admin/dashboard.php");
        } else {
            header("Location: ../views/prestamista/proceso.php");
        }
        exit;
    } else {
        // Credenciales incorrectas
        echo "<script>alert('Credenciales incorrectas'); window.location.href = '../views/login.php';</script>";
    }
}
?>
