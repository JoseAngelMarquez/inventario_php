<?php
require_once '../../config/db.config.php';
require_once '../../model/Users.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$userModel = new Users($conexion);
$controlUsuarios = $userModel->obtenerTodos();

$userEditar = null;
if (isset($_GET['editar'])) {
    $userEditar = $userModel->obtenerPorId($_GET['editar']);
}

// Mostrar mensajes si los hay
$mensaje = '';
if (isset($_GET['success'])) {
    $mensaje = '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
} elseif (isset($_GET['error'])) {
    $mensaje = '<div class="alert alert-error">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Control de usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="/asset/user/prestamo.css" />
    <link rel="stylesheet" href="/asset/users/esqueleto.css" />
    <link rel="stylesheet" href="/asset/admin/usuarios.css" />
</head>

<body>
    <div class="container">
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

        <div class="main-content">
            <div class="top-bar">
                <img class="uaeh" src="/asset/img/logo_uaeh.png" alt="icono uaeh" width="150" />
                <span class="software-name">uaeh</span>
                <h6>Bienvenido <?= htmlspecialchars($_SESSION['usuario']) ?></h6>
                <i class="fas fa-user-circle avatar"></i>
            </div>

            <div class="content">
                <h1 class="h1">Control de usuarios</h1>

                <?= $mensaje ?>

                <!-- Formulario Crear o Editar -->
                <?php if ($userEditar): ?>
                    <h2>Editar Usuario #<?= htmlspecialchars($userEditar['id']) ?></h2>
                    <form method="POST" action="../../controller/UsersController.php">
                        <input type="hidden" name="accion" value="editar" />
                        <input type="hidden" name="id" value="<?= htmlspecialchars($userEditar['id']) ?>" />
                        <input type="text" name="usuario" value="<?= htmlspecialchars($userEditar['usuario']) ?>" placeholder="Usuario" required />
                        <input type="password" name="contrasena" placeholder="Nueva contraseña (dejar vacío para no cambiar)" />
                        <select name="rol" required>
                            <option value="">Seleccione rol</option>
                            <option value="admin" <?= $userEditar['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="prestamista" <?= $userEditar['rol'] === 'prestamista' ? 'selected' : '' ?>>Prestamista</option>
                        </select>
                        <button type="submit">Actualizar Usuario</button>
                        <a href="controlUsuarios.php" style="margin-left: 10px;">Cancelar</a>
                    </form>
                <?php else: ?>
                    <h2>Crear Nuevo Usuario</h2>
                    <form method="POST" action="../../controller/UsersController.php">
                        <input type="hidden" name="accion" value="crear" />
                        <input type="text" name="usuario" placeholder="Usuario" required />
                        <input type="password" name="contrasena" placeholder="Contraseña" required />
                        <select name="rol" required>
                            <option value="">Seleccione rol</option>
                            <option value="admin">Admin</option>
                            <option value="prestamista">Prestamista</option>
                        </select>
                        <button type="submit">Crear Usuario</button>
                    </form>
                <?php endif; ?>

                <!-- Tabla de usuarios -->
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
                                    <form action="controlUsuarios.php" method="GET" style="display:inline;">
                                        <input type="hidden" name="editar" value="<?= $row['id'] ?>" />
                                        <button type="submit" class="btn-unico">Editar</button>
                                    </form>
                                    <form method="POST" action="../../controller/UsersController.php" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        <input type="hidden" name="eliminar" value="<?= $row['id'] ?>" />
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
