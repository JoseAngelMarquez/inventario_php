<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Prestamos.php';

// Incluir PHPMailer
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

function enviarCorreoSimple($correoDestino, $nombreDestino, $asunto, $mensaje) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'joseangelmarquezespina060503@gmail.com';       // Cambia aquí a tu correo
        $mail->Password   = 'zxzv gsit xdpu hbip';           // Cambia aquí a tu app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('tu_correo@gmail.com', 'Sistema de Préstamos');
        $mail->addAddress($correoDestino, $nombreDestino);

        $mail->isHTML(false);
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
    }
}
$prestamos = new Prestamos($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion'])) {

        if ($_POST['accion'] === 'prestar') {

            $tipo = $_POST['tipo'];
            $nombre_completo = $_POST['nombre_completo'];
            $matricula = $_POST['matricula'] ?? null;
            $carrera = $_POST['carrera'] ?? null;
            $lugar_trabajo = $_POST['lugar_trabajo'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $correo = $_POST['correo'] ?? null;

            $id_solicitante = $prestamos->registrarSolicitante($tipo, $nombre_completo, $matricula, $carrera, $lugar_trabajo, $telefono, $correo);

            if ($id_solicitante) {
                $id_material = $_POST['id_material'];
                $cantidad = $_POST['cantidad'];
                $id_usuario = $_SESSION['id_usuario'];

                if ($prestamos->registrarPrestamo($id_material, $cantidad, $id_usuario, $id_solicitante)) {
                    // Enviar correo al solicitante
                    if (!empty($correo)) {
                        $asunto = "Préstamo registrado";
                        $mensaje = "Hola $nombre_completo,\n\nTu préstamo ha sido registrado correctamente.\n\nGracias por usar nuestro sistema.";
                        enviarCorreoSimple($correo, $nombre_completo, $asunto, $mensaje);
                    }
                    header("Location: ../views/common/Prestamos.php?success=Préstamo registrado correctamente");
                    exit();
                } else {
                    header("Location: ../views/common/Prestamos.php?error=No se pudo registrar el préstamo");
                    exit();
                }
            } else {
                header("Location: ../views/common/Prestamos.php?error=No se pudo registrar el solicitante");
                exit();
            }
        }

        if ($_POST['accion'] === 'finalizar') {
            $id_prestamo = $_POST['id_prestamo'];
            $id_finalizado_por = $_SESSION['id_usuario'];

            if ($prestamos->finalizarPrestamo($id_prestamo, $id_finalizado_por)) {
                // Obtener datos del préstamo para enviar correo al solicitante
                $datosPrestamo = $prestamos->obtenerDatosPrestamo($id_prestamo);

                if (!empty($datosPrestamo['correo'])) {
                    $asunto = "Préstamo finalizado";
                    $mensaje = "Hola {$datosPrestamo['nombre_completo']},\n\nTu préstamo ha sido finalizado correctamente.\n\nGracias por usar nuestro sistema.";
                    enviarCorreoSimple($datosPrestamo['correo'], $datosPrestamo['nombre_completo'], $asunto, $mensaje);
                }
                header("Location: ../views/common/prestamos.php?success=Préstamo finalizado correctamente");
                exit();
            } else {
                header("Location: ../views/common/prestamos.php?error=No se pudo finalizar el préstamo");
                exit();
            }
        }
    }
}

header("Location: ../views/common/prestamos.php?error=Acción no válida");
exit();
?>