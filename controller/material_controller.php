<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Material.php';

$material = new Material($conn);

// Acciones: insertar, actualizar, eliminar, buscar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'insertar':
            $material->insertar($_POST['nombre'], $_POST['descripcion'], $_POST['cantidad'], $_POST['categoria']);
            break;
        case 'actualizar':
            $material->actualizar($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['cantidad'], $_POST['categoria']);
            break;
        case 'eliminar':
            $material->eliminar($_POST['id']);
            break;
    }

    header("Location: ../views/admin/materiales.php");
    exit();
}
