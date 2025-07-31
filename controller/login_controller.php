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
                return "Bienvenido, {$usuarioEncontrado['rol']}. Tu ID es: {$usuarioEncontrado['id']}";
            } else {
                return "Contraseña incorrecta.";
            }
        } else {
            return "Usuario no encontrado.";
        }
    }
}
?>
