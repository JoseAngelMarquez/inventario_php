<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Prestamos.php';

session_start();

$prestamos = new Prestamos($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Registrar préstamo
    if (isset($_POST['accion']) && $_POST['accion'] === 'prestar') {

        // Datos solicitante
        $tipo = $_POST['tipo'];
        $nombre_completo = $_POST['nombre_completo'];
        $matricula = $_POST['matricula'] ?? null;
        $carrera = $_POST['carrera'] ?? null;
        $lugar_trabajo = $_POST['lugar_trabajo'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        $correo = $_POST['correo'] ?? null;

        // Guardar solicitante
        $id_solicitante = $prestamos->registrarSolicitante($tipo, $nombre_completo, $matricula, $carrera, $lugar_trabajo, $telefono, $correo);

        if ($id_solicitante) {
            // Datos préstamo
            $id_material = $_POST['id_material'];
            $cantidad = $_POST['cantidad'];
            $id_usuario = $_SESSION['id_usuario']; 

            if ($prestamos->registrarPrestamo($id_material, $cantidad, $id_usuario, $id_solicitante)) {
                header("Location: ../views/admin/prestamos.php?success=Préstamo registrado correctamente");
                exit();
            } else {
                header("Location: ../views/admin/prestamos.php?error=No se pudo registrar el préstamo");
                exit();
            }
        } else {
            header("Location: ../views/admin/prestamos.php?error=No se pudo registrar el solicitante");
            exit();
        }
    }

    // Finalizar préstamo
    if (isset($_POST['accion']) && $_POST['accion'] === 'finalizar') {
        $id_prestamo = $_POST['id_prestamo'];
        $id_finalizado_por = $_SESSION['id_usuario'];

        if ($prestamos->finalizarPrestamo($id_prestamo, $id_finalizado_por)) {
            header("Location: ../views/admin/prestamos.php?success=Préstamo finalizado correctamente");
            exit();
        } else {
            header("Location: ../views/admin/prestamos.php?error=No se pudo finalizar el préstamo");
            exit();
        }
    }
}

// Si no hay acción válida
header("Location: ../views/admin/prestamos.php?error=Acción no válida");
exit();
?>
