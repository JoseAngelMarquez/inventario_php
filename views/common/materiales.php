<?php
require_once '../../config/db.config.php';
require_once '../../model/Material.php';
session_start();

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'prestamista'])) {
    require_once __DIR__ . '/../login.php';
    exit();
}


$materialModel = new Material($conexion);
$materiales = $materialModel->obtenerTodos();

$materialEditar = null;
if (isset($_GET['editar'])) {
    $materialEditar = $materialModel->obtenerPorId($_GET['editar']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Gestión de Materiales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/asset/users/contenido.css">
    <link rel="stylesheet" href="/asset/users/esqueleto.css">
    <link rel="stylesheet" href="/asset/users/materiales.css">

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
                <a href="../../controller/logout.php"
                    onclick="return confirm('¿Seguro que deseas cerrar sesión?')">Salir</a>
            </div>
        </nav>


        <div class="main-content">
            <div class="top-bar">
                <img class="uaeh" src="/asset/img/logo_uaeh.png" alt="icono uaeh" width="150">
                <span class="software-name">uaeh</span>
                <h6>Bienvenido <?= htmlspecialchars($_SESSION['usuario']) ?></h6>
                <i class="fas fa-user-circle avatar"></i>
            </div>

            <div class="content">
                <h2><?= $materialEditar ? 'Editar' : 'Agregar' ?> Material</h2>
                <form class="form-principal" action="../../controller/material_controller.php" method="POST">
                    <?php if ($materialEditar): ?>
                        <input type="hidden" name="id" value="<?= $materialEditar['id'] ?>" />
                    <?php endif; ?>

                    <input type="text" name="nombre" required placeholder="Nombre"
                        value="<?= htmlspecialchars($materialEditar['nombre'] ?? '') ?>" /><br />

                    <select name="tipo" required>
                        <option value="">Seleccione tipo</option>
                        <option value="herramienta manual" <?= (isset($materialEditar['tipo']) && $materialEditar['tipo'] === 'herramienta manual') ? 'selected' : '' ?>>Herramienta manual
                        </option>
                        <option value="herramienta eléctrica" <?= (isset($materialEditar['tipo']) && $materialEditar['tipo'] === 'herramienta eléctrica') ? 'selected' : '' ?>>Herramienta
                            eléctrica</option>
                        <option value="insumo" <?= (isset($materialEditar['tipo']) && $materialEditar['tipo'] === 'insumo') ? 'selected' : '' ?>>Insumo</option>
                    </select><br />

                    <input type="number" name="cantidad" required placeholder="Cantidad"
                        value="<?= htmlspecialchars($materialEditar['cantidad_disponible'] ?? '') ?>" /><br />

                    <textarea name="descripcion"
                        placeholder="Descripción"><?= htmlspecialchars($materialEditar['descripcion'] ?? '') ?></textarea><br />

                    <button type="submit" class="btn-unico" name="<?= $materialEditar ? 'editar' : 'agregar' ?>">
                        <?= $materialEditar ? 'Actualizar' : 'Agregar' ?>
                    </button>
                </form>

                <hr />

                <h2>Lista de Materiales</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $materiales->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nombre']) ?></td>
                                <td><?= htmlspecialchars($row['tipo']) ?></td>
                                <td><?= $row['cantidad_disponible'] ?></td>
                                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                                <td>
                                    <form action="materiales.php" method="GET" style="display:inline;">
                                        <input type="hidden" name="editar" value="<?= $row['id'] ?>" />
                                        <button type="submit" class="btn-table">Editar</button>
                                    </form> |
                                    <form action="../../controller/material_controller.php" method="GET"
                                        style="display:inline-block;">
                                        <input type="hidden" name="eliminar" value="<?= $row['id'] ?>" />
                                        <button type="submit" class="btn-table"
                                            onclick="return confirm('¿Eliminar este material?')">Eliminar</button>
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