<?php
require_once __DIR__ . '/../../config/db.config.php';
require_once __DIR__ . '/../../model/prestamos.php';
session_start();

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'prestamista'])) {
    header('Location: ../login.php');
    exit();
}

$prestamos = new Prestamos($conexion);
$historial = $prestamos->obtenerPrestamos();

// Obtener materiales disponibles para el select
$materiales = $conexion->query("SELECT id, nombre, cantidad_disponible FROM materiales WHERE cantidad_disponible > 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Préstamos</title>
    <link rel="stylesheet" href="/asset/users/esqueleto.css">
</head>
<body>
    <h1>Registrar Préstamo</h1>
    <form method="POST" action="../../controller/PrestamosController.php">
        <input type="hidden" name="accion" value="prestar">

        <h3>Datos del solicitante</h3>
        <select name="tipo" required>
            <option value="">Seleccione tipo</option>
            <option value="estudiante">Estudiante</option>
            <option value="trabajador">Trabajador</option>
        </select>
        <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
        <input type="text" name="matricula" placeholder="Matrícula">
        <input type="text" name="carrera" placeholder="Carrera">
        <input type="text" name="lugar_trabajo" placeholder="Lugar de trabajo">
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="email" name="correo" placeholder="Correo">

        <h3>Material a prestar</h3>
        <select name="id_material" required>
            <option value="">Seleccione material</option>
            <?php while ($mat = $materiales->fetch_assoc()): ?>
                <option value="<?= $mat['id'] ?>">
                    <?= htmlspecialchars($mat['nombre']) ?> (Disponible: <?= $mat['cantidad_disponible'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <input type="number" name="cantidad" placeholder="Cantidad" min="1" required>

        <button type="submit">Prestar</button>
    </form>

    <h2>Historial de préstamos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Material</th>
            <th>Cantidad</th>
            <th>Prestado por</th>
            <th>Solicitante</th>
            <th>Fecha préstamo</th>
            <th>Estado</th>
        </tr>
        <?php while ($row = $historial->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['material'] ?></td>
            <td><?= $row['cantidad'] ?></td>
            <td><?= $row['prestado_por'] ?></td>
            <td><?= $row['solicitante'] ?></td>
            <td><?= $row['fecha_prestamo'] ?></td>
            <td><?= $row['estado'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
