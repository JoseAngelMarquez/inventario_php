<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Users.php';

session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../views/login.php');
    exit();
}

$userModel = new Users($conexion);

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    if ($usuario && $contrasena && $rol) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        if ($userModel->insertar($usuario, $hash, $rol)) {
            header('Location: ../views/admin/controlUsuarios.php?success=Usuario creado');
        } else {
            header('Location: ../views/admin/controlUsuarios.php?error=Error al crear usuario');
        }
    } else {
        header('Location: ../views/admin/controlUsuarios.php?error=Todos los campos son obligatorios');
    }
    exit();
}

// Editar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id = intval($_POST['id']);
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    if ($usuario && $rol) {
        // Si la contraseña viene vacía, conservar la actual
        if (empty($contrasena)) {
            $datosActuales = $userModel->obtenerPorId($id);
            $hash = $datosActuales['contrasena'];
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        }

        if ($userModel->actualizar($id, $usuario, $hash, $rol)) {
            header('Location: ../views/admin/controlUsuarios.php?success=Usuario actualizado');
        } else {
            header('Location: ../views/admin/controlUsuarios.php?error=Error al actualizar usuario');
        }
    } else {
        header('Location: ../views/admin/controlUsuarios.php?error=Usuario y rol son obligatorios');
    }
    exit();
}

// Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = intval($_POST['eliminar']);
    if ($userModel->eliminar($id)) {
        header('Location: ../views/admin/controlUsuarios.php?success=Usuario eliminado');
    } else {
        header('Location: ../views/admin/controlUsuarios.php?error=Error al eliminar usuario');
    }
    exit();
}
