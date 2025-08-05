<?php
class Users
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM usuarios";
        return $this->conexion->query($sql);
    }

    public function insertar($usuario, $contrasena, $rol)
    {
        $stmt = $this->conexion->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $contrasena, $rol);
        return $stmt->execute();
    }


    // Obtener usuario por ID
    public function obtenerPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Actualizar usuario
    public function actualizar($id, $usuario, $contrasena, $rol)
{
    $stmt = $this->conexion->prepare("UPDATE usuarios SET usuario = ?, contrasena = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("sssi", $usuario, $contrasena, $rol, $id);
    return $stmt->execute();
}


    // Eliminar usuario
    public function eliminar($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerUsuarioPorNombre($usuario)
    {
        $stmt = $this->conexion->prepare("SELECT id, usuario, contrasena, rol FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}
