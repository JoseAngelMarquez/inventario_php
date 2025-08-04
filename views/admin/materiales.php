<?php
require_once '../../config/db.config.php';
require_once '../../model/Material.php';

$material = new Material($conn);

$modo_edicion = false;
$material_editar = [
    'id' => '',
    'nombre' => '',
    'descripcion' => '',
    'cantidad' => '',
    'categoria' => ''
];

// Si estamos editando
if (isset($_GET['editar_id'])) {
    $modo_edicion = true;
    $material_editar = $material->obtenerPorId($_GET['editar_id']);
}

$lista = $material->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Materiales</title>
    <link rel="stylesheet" href="/asset/user/prestamo.css">

    <link rel="stylesheet" href="../../esqueleto.css">
</head>
<body>

    <h2><?= $modo_edicion ? 'Editar Material' : 'Agregar Material' ?></h2>

    <form action="../../controller/material_controller.php" method="POST">
        <input type="hidden" name="accion" value="<?= $modo_edicion ? 'actualizar' : 'insertar' ?>">
        <?php if ($modo_edicion): ?>
            <input type="hidden" name="id" value="<?= $material_editar['id'] ?>">
        <?php endif; ?>

        <input type="text" name="nombre" placeholder="Nombre del material" value="<?= htmlspecialchars($material_editar['nombre']) ?>" required>
        <input type="text" name="descripcion" placeholder="Descripción" value="<?= htmlspecialchars($material_editar['descripcion']) ?>">
        <input type="number" name="cantidad" placeholder="Cantidad" value="<?= htmlspecialchars($material_editar['cantidad']) ?>" required>
        <input type="text" name="categoria" placeholder="Categoría" value="<?= htmlspecialchars($material_editar['categoria']) ?>">

        <button type="submit"><?= $modo_edicion ? 'Actualizar' : 'Agregar' ?></button>

        <?php if ($modo_edicion): ?>
            <a href="materiales.php">Cancelar</a>
        <?php endif; ?>
    </form>
    <nav class="sidebar">
            <ul>
                <li><a href="prestamos.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="controlUsuarios.php"><i class="fas fa-users"></i> Control de usuarios</a></li>
                <li><a href="prestamos.php"><i class="fas fa-chart-line"></i> Préstamos</a></li>
                <li><a href="materiales.php"><i class="fas fa-chart-line"></i> Materiales</a></li>

            </ul>
            <div class="logout">
            <a href="/controller/logout.php" onclick="return confirm('¿Seguro que deseas cerrar sesión?')">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
            </div>
        </nav>
    <h3>Lista de Materiales</h3>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Categoría</th>
            <th>Acciones</th>
        </tr>
        <?php while ($m = $lista->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($m['nombre']) ?></td>
                <td><?= htmlspecialchars($m['descripcion']) ?></td>
                <td><?= $m['cantidad'] ?></td>
                <td><?= htmlspecialchars($m['categoria']) ?></td>
                <td>
                    <form action="materiales.php" method="GET" style="display:inline;">
                        <input type="hidden" name="editar_id" value="<?= $m['id'] ?>">
                        <button type="submit">Editar</button>
                    </form>

                    <form action="../../controller/material_controller.php" method="POST" style="display:inline;">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                        <button type="submit" onclick="return confirm('¿Eliminar este material?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
