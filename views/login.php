<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../controller/login_controller.php';

$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $loginController = new LoginController();
    list($exito, $mensaje) = $loginController->login($usuario, $contrasena);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="/asset/login/login.css">
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
</head>
<body>
    <div class="container">
    <div class="right-side">
        </div>
        <div class="left-side">
            
            <h2>Inicio de Sesión</h2>
            <form method="POST" action="">
                <img src="/asset/img/logo_login.png" alt="logo" class="logo-login" >
                <label>Usuario:</label>
                <input type="text" name="usuario" required><br><br>

                <label>Contraseña:</label>
                <input type="password" name="contrasena" required><br><br>

                <button type="submit">Ingresar</button>
            </form>

    <?php if (!empty($mensaje)) : ?>
        <p style="color: <?= $exito ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($mensaje) ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_SESSION['id_usuario'])) : ?>
        <p>Sesión activa - ID: <?= $_SESSION['id_usuario']; ?>, Rol: <?= $_SESSION['rol']; ?></p>
    <?php endif; ?>
</body>
</html>
