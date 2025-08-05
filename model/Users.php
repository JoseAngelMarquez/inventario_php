<?php
class Users {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios";
        return $this->conexion->query($sql);
    }

    public function insertar($usuario, $contrasena, $rol) {
        $stmt = $this->conexion->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $contrasena, $rol);
        return $stmt->execute();
    }
    

}
