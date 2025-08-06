<?php
session_start();

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'prestamista'])) {
    require_once __DIR__ . '/../login.php';
    exit();
}

require_once __DIR__ . '/../../config/db.config.php';
require_once __DIR__ . '/../../model/Prestamos.php';

$prestamosModel = new Prestamos($conexion);
$listaPrestamos = $prestamosModel->obtenerTodos(); // Necesita método en el modelo para listar
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/asset/user/prestamo.css">
    <link rel="stylesheet" href="/asset/users/esqueleto.css">
    <title>Historial de préstamos</title>
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
                <h1 class="h1">Materiales prestados</h1>

                <!-- Barra de búsqueda -->
                <div>
                    <p>Nombre del material:</p>
                    <div style="position: relative;">
                        <input type="text" id="materialName" placeholder="Buscar material..." style="padding-left: 30px; width: 50vh;">
                        <i class="fa-solid fa-magnifying-glass"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: gray;"></i>
                    </div>
                </div>

                <!-- Tabla -->
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Material prestado</th>
                            <th>Fecha de préstamo</th>
                            <th>Estado</th>
                            <th>Cantidad</th>
                            <th>Solicitante</th>
                            <th>Finalización</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($prestamo = $listaPrestamos->fetch_assoc()): ?>
                            <tr>
                                <td><?= $prestamo['id'] ?></td>
                                <td><?= htmlspecialchars($prestamo['usuario']) ?></td>
                                <td><?= htmlspecialchars($prestamo['material']) ?></td>
                                <td><?= $prestamo['fecha_prestamo'] ?></td>
                                <td><?= $prestamo['estado'] ?></td>
                                <td><?= $prestamo['cantidad'] ?></td>
                                <td><?= htmlspecialchars($prestamo['solicitante']) ?></td>
                                <td><?= $prestamo['fecha_devolucion'] ?? '—' ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</body>

</html>
