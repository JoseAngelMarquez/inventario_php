<?php
require_once __DIR__ . '/../config/db.config.php';

class Usuario {
    private $conexion;

    public function __construct() {
        global $conexion;  
        $this->conexion = $conexion;
    }

    public function obtenerUsuarioPorNombre($usuario) {
        $stmt = $this->conexion->prepare("SELECT id, usuario, contrasena, rol FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuarioEncontrado = $resultado->fetch_assoc();
        $stmt->close();
        return $usuarioEncontrado;
    }
}
?>
