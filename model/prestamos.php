<?php
class Prestamos
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Registrar solicitante y devolver su ID
    public function registrarSolicitante($tipo, $nombre_completo, $matricula, $carrera, $lugar_trabajo, $telefono, $correo)
    {
        $stmt = $this->conexion->prepare("
            INSERT INTO solicitantes (tipo, nombre_completo, matricula, carrera, lugar_trabajo, telefono, correo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssss", $tipo, $nombre_completo, $matricula, $carrera, $lugar_trabajo, $telefono, $correo);

        if ($stmt->execute()) {
            return $this->conexion->insert_id; // ID del solicitante
        }
        return false;
    }

    // Registrar préstamo
    public function registrarPrestamo($id_material, $cantidad, $id_usuario, $id_solicitante)
{
    // Comprobar stock antes de prestar
    $stmt = $this->conexion->prepare("SELECT cantidad_disponible FROM materiales WHERE id = ?");
    $stmt->bind_param("i", $id_material);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if (!$resultado) {
        return false; // Material no encontrado
    }

    if ($cantidad > $resultado['cantidad_disponible']) {
        return false; // No hay suficiente stock
    }

    $fecha_prestamo = date('Y-m-d H:i:s');

    // Insertar préstamo
    $stmt = $this->conexion->prepare("
        INSERT INTO prestamos (id_material, cantidad, fecha_prestamo, estado, id_usuario, id_solicitante)
        VALUES (?, ?, ?, 'prestado', ?, ?)
    ");
    $stmt->bind_param("iisii", $id_material, $cantidad, $fecha_prestamo, $id_usuario, $id_solicitante);

    if ($stmt->execute()) {
        // Actualizar stock
        $this->actualizarStock($id_material, -$cantidad);
        return true;
    }
    return false;
}


    // Finalizar préstamo
    public function finalizarPrestamo($id_prestamo, $id_finalizado_por)
    {
        $fecha_devolucion = date('Y-m-d H:i:s');

        // Obtener préstamo
        $stmt = $this->conexion->prepare("SELECT id_material, cantidad FROM prestamos WHERE id = ?");
        $stmt->bind_param("i", $id_prestamo);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado) {
            $id_material = $resultado['id_material'];
            $cantidad = $resultado['cantidad'];

            // Actualizar estado del préstamo
            $stmt2 = $this->conexion->prepare("
                UPDATE prestamos
                SET estado = 'finalizado', fecha_devolucion = ?, id_finalizado_por = ?
                WHERE id = ?
            ");
            $stmt2->bind_param("sii", $fecha_devolucion, $id_finalizado_por, $id_prestamo);

            if ($stmt2->execute()) {
                // Devolver cantidad al stock
                $this->actualizarStock($id_material, $cantidad);
                return true;
            }
        }
        return false;
    }

    // Actualizar stock de materiales
    private function actualizarStock($id_material, $cantidad_cambio)
    {
        $stmt = $this->conexion->prepare("
            UPDATE materiales
            SET cantidad_disponible = cantidad_disponible + ?
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $cantidad_cambio, $id_material);
        return $stmt->execute();
    }

    // Obtener todos los préstamos con detalles
    public function obtenerPrestamos()
    {
        $sql = "
            SELECT p.id, m.nombre AS material, p.cantidad, p.fecha_prestamo, p.fecha_devolucion, p.estado,
                   u.usuario AS prestado_por, sf.nombre_completo AS solicitante
            FROM prestamos p
            INNER JOIN materiales m ON p.id_material = m.id
            INNER JOIN usuarios u ON p.id_usuario = u.id
            INNER JOIN solicitantes sf ON p.id_solicitante = sf.id
            ORDER BY p.fecha_prestamo DESC
        ";
        return $this->conexion->query($sql);
    }
    // Obtener datos del préstamo (nombre y correo del solicitante)
public function obtenerDatosPrestamo($id_prestamo)
{
    $sql = "
        SELECT s.nombre_completo, s.correo
        FROM prestamos p
        INNER JOIN solicitantes s ON p.id_solicitante = s.id
        WHERE p.id = ?
        LIMIT 1
    ";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        error_log("Error preparando consulta obtenerDatosPrestamo: " . $this->conexion->error);
        return null;
    }
    $stmt->bind_param("i", $id_prestamo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado && $resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    }
    return null;
}

}


?>
