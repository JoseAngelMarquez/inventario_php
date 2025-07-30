<?php
require_once __DIR__ . '/../config/db.config.php';

class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function verificarUsuario($usuario, $contrasena) {
        $sql = "SELECT * FROM usuarios WHERE usuario = ? AND contrasena = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $usuario, $contrasena);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc(); // Retorna el usuario como array asociativo o false
    }
}
