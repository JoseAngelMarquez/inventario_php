<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/asset/login/login.css">
    <title>Inicio de sesión</title>
    <meta charset="UTF-8">
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

            <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

            <?php
            if (isset($_SESSION['id_usuario'])) {
                echo "<p>Sesión activa - ID Usuario: " . $_SESSION['id_usuario'] . ", Rol: " . $_SESSION['rol'] . "</p>";
            }
            ?>
        </div>
        
    </div>
</body>
</html>
