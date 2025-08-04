<?php
require_once __DIR__ . '/../model/Usuario.php';

class LoginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($usuario, $contrasena) {
        $usuarioEncontrado = $this->usuarioModel->obtenerUsuarioPorNombre($usuario);

        if ($usuarioEncontrado) {
            if ($usuarioEncontrado['contrasena'] === $contrasena) {
                $_SESSION['id_usuario'] = $usuarioEncontrado['id'];
                $_SESSION['usuario'] = $usuarioEncontrado['usuario'];
                $_SESSION['rol'] = $usuarioEncontrado['rol'];

                // Redirección según el rol
                if ($_SESSION['rol'] === 'admin') {
                    header('Location: /views/admin/prestamos.php');
                } else if ($_SESSION['rol'] === 'prestamista') {
                    header('Location: /views/prestamista/panel.php');
                }
                exit(); 
            } else {
                return [false, "Contraseña incorrecta"];
            }
        } else {
            return [false, "Usuario no encontrado"];
        }
    }
}
?>
