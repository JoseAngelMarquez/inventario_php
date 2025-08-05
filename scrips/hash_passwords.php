<?php
require_once __DIR__ . '/../config/db.config.php'; // Ajusta la ruta

$result = $conexion->query("SELECT id, contrasena FROM usuarios");

while ($row = $result->fetch_assoc()) {
    $password = $row['contrasena'];

    // Si no está hasheada
    if (strpos($password, '$2y$') !== 0) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $row['id']);
        $stmt->execute();

        echo "Usuario con ID {$row['id']} actualizado.<br>";
    }
}

echo "Proceso completado.";
