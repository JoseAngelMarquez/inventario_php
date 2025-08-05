<?php
require_once '../../config/db.config.php';
require_once '../../model/Users.php'; // Cambié a Users.php, no Material.php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    require_once __DIR__ . '/../login.php';
    exit();
}

// Crear instancia del modelo
$userModel = new Users($conexion);

// Obtener todos los usuarios
$controlUsuarios = $userModel->obtenerTodos();

// Si se quiere editar un usuario específico
$userEditar = null;
if (isset($_GET['editar'])) {
    $userEditar = $userModel->obtenerPorId($_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/asset/user/prestamo.css">
    <link rel="stylesheet" href="/asset/users/esqueleto.css">
    <title>Control de usuarios</title>
</head>

<body>
    <div class="container">
        <!-- Menú lateral izquierdo -->
        <nav class="sidebar">
            <?php
            if ($_SESSION['rol'] === 'admin') {
                require_once __DIR__ . '/../includes/menu_admin.php';
            } elseif ($_SESSION['rol'] === 'prestamista') {
                require_once __DIR__ . '/../includes/menu_prestamista.php';
            }
            ?>
            <div class="logout">
                <a href="/controller/logout.php" onclick="return confirm('¿Seguro que deseas cerrar sesión?')">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="top-bar">
                <img class="uaeh" src="/asset/img/logo_uaeh.png" alt="icono uaeh" width="150">
                <span class="software-name">uaeh</span>
                <h6>Bienvenido <?= htmlspecialchars($_SESSION['usuario']) ?></h6>
                <i class="fas fa-user-circle avatar"></i>
            </div>

            <!-- Resto del contenido -->
            <div class="content">
                <h1 class="h1">Control de usuarios</h1>

                <!-- Botón para crear nuevo usuario -->
                <a href="crear_usuario.php" class="btn-unico" style="margin-bottom: 10px; display:inline-block;">Nuevo Usuario</a>

                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $controlUsuarios->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['usuario']) ?></td>
                                    <td><?= htmlspecialchars($row['rol']) ?></td>
                                    <td>
                                        <!-- Botón Editar -->
                                        <form action="../../controller/UsersController.php" method="GET" style="display:inline;">
                                            <input type="hidden" name="editar" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn-unico">Editar</button>
                                        </form>
                                        <!-- Botón Eliminar -->
                                        <form action="../../controller/UsersController.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                            <input type="hidden" name="eliminar" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn-unico" style="background:#a00;">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
</body>
</html>
