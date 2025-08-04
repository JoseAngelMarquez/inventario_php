<?php
require_once '../../config/db.config.php';
require_once '../../model/Material.php';

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
    <link rel="stylesheet" href="../../esqueleto.css" />
</head>
<body>
    <h2><?= $materialEditar ? 'Editar' : 'Agregar' ?> Material</h2>
    <form action="../../controller/material_controller.php" method="POST">
        <?php if ($materialEditar): ?>
            <input type="hidden" name="id" value="<?= $materialEditar['id'] ?>" />
        <?php endif; ?>

        <input type="text" name="nombre" required placeholder="Nombre" value="<?= htmlspecialchars($materialEditar['nombre'] ?? '') ?>" /><br />

        <select name="tipo" required>
            <option value="">Seleccione tipo</option>
            <option value="herramienta manual" <?= (isset($materialEditar['tipo']) && $materialEditar['tipo'] === 'herramienta manual') ? 'selected' : '' ?>>Herramienta manual</option>
            <option value="herramienta eléctrica" <?= (isset($materialEditar['tipo']) && $materialEditar['tipo'] === 'herramienta eléctrica') ? 'selected' : '' ?>>Herramienta eléctrica</option>
            <option value="insumo" <?= (isset($materialEditar['tipo']) && $materialEditar['tipo'] === 'insumo') ? 'selected' : '' ?>>Insumo</option>
        </select><br />

        <input type="number" name="cantidad" required placeholder="Cantidad" value="<?= htmlspecialchars($materialEditar['cantidad_disponible'] ?? '') ?>" /><br />

        <textarea name="descripcion" placeholder="Descripción"><?= htmlspecialchars($materialEditar['descripcion'] ?? '') ?></textarea><br />

        <button type="submit" name="<?= $materialEditar ? 'editar' : 'agregar' ?>">
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
                        <a href="materiales.php?editar=<?= $row['id'] ?>">Editar</a> |
                        <a href="../../controller/material_controller.php?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este material?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
