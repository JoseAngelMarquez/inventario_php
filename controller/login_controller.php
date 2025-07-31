<?php
require_once __DIR__ . '/../model/Usuario.php';

class LoginController {
    private $usuarioModel;

    public function __construct($conexion) {
        $this->usuarioModel = new Usuario($conexion);
    }

    public function login($usuario, $contrasena) {
        session_start();
        $usuarioEncontrado = $this->usuarioModel->obtenerUsuarioPorNombre($usuario);

        if ($usuarioEncontrado) {
            if ($usuarioEncontrado['contrasena'] === $contrasena) {
                $_SESSION['id_usuario'] = $usuarioEncontrado['id'];
                $_SESSION['rol'] = $usuarioEncontrado['rol'];
                 // Redirigir según el rol
                 switch ($usuarioEncontrado['rol']) {
                    case 'admin':
                    case 'prestamista':
                        header("Location: ../views/admin/prestamos.php");
                        break;
                    default:
                        header("Location: ../views/error.php");
                }

                exit(); 
            } else {
                return "Contraseña incorrecta.";
            }
        } else {
            return "Usuario no encontrado.";
        }
    }
}
?>
