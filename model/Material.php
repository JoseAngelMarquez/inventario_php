<?php
require_once __DIR__ . '/../config/db.config.php';

class Material {
    private $conexion;

    public function __construct($conexion) {
        global $conexion; 
        $this->conexion = $conexion;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM materiales";
        return $this->conexion->query($sql);
    }

    public function insertar($nombre, $descripcion, $cantidad, $categoria) {
        $stmt = $this->conexion->prepare("INSERT INTO materiales (nombre, descripcion, cantidad, categoria) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nombre, $descripcion, $cantidad, $categoria);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM materiales WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizar($id, $nombre, $descripcion, $cantidad, $categoria) {
        $stmt = $this->conexion->prepare("UPDATE materiales SET nombre = ?, descripcion = ?, cantidad = ?, categoria = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $nombre, $descripcion, $cantidad, $categoria, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conexion->prepare("DELETE FROM materiales WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
