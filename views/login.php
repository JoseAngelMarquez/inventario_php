<!DOCTYPE html>
<html>
<head>
    <title>Login MVC</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Inicio de Sesión</h2>
    <form method="POST" action="">
        <label>Usuario:</label>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit">Ingresar</button>
    </form>

    <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

    <?php
    if (isset($_SESSION['id_usuario'])) {
        echo "<p>Sesión activa - ID Usuario: " . $_SESSION['id_usuario'] . ", Rol: " . $_SESSION['rol'] . "</p>";
    }
    ?>
</body>
</html>
