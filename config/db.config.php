<?php
date_default_timezone_set('America/Mexico_City');
$host = "localhost";
$user = "root";
$password = "JAME060503";
$db = "sistema_inventario";

$conexion = new mysqli($host, $user, $password, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexioSn->connect_error);
}


?>
