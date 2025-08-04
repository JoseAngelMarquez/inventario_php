<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Material.php';

$material = new Material($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $tipo = $_POST['tipo'];

    if (isset($_POST['agregar'])) {
        $material->insertar($nombre, $descripcion, $cantidad, $tipo);
    } elseif (isset($_POST['editar'])) {
        $material->actualizar($id, $nombre, $descripcion, $cantidad, $tipo);
    }

    header("Location: ../views/admin/materiales.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $material->eliminar($_GET['eliminar']);
    header("Location: ../views/admin/materiales.php");
    exit();
}
