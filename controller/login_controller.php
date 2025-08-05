<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Users.php';

class LoginController {
    private $usuarioModel;

    public function __construct($conexion) {
        $this->usuarioModel = new Users($conexion);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($usuario, $contrasena) {
        $usuarioEncontrado = $this->usuarioModel->obtenerUsuarioPorNombre($usuario);

        if ($usuarioEncontrado) {
            if (password_verify($contrasena, $usuarioEncontrado['contrasena'])) {
                $_SESSION['id_usuario'] = $usuarioEncontrado['id'];
                $_SESSION['usuario'] = $usuarioEncontrado['usuario'];
                $_SESSION['rol'] = $usuarioEncontrado['rol'];

                // Redirección según rol
                header('Location: /views/common/Home.php');
                exit();
            } else {
                return [false, "Contraseña incorrecta"];
            }
        } else {
            return [false, "Usuario no encontrado"];
        }
    }
}
