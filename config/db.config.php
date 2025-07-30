<?php
$_SERVER['DB_HOST'] = 'localhost';
$_SERVER['DB_NAME'] = 'sistema_inventario';
$_SERVER['DB_PASSWORD'] = 'JAME060503';
$_SERVER['DB_USERNAME'] = 'root';

$conexion = new mysqli(
    $_SERVER['DB_HOST'],
    $_SERVER['DB_USERNAME'],
    $_SERVER['DB_PASSWORD'],
    $_SERVER['DB_NAME']
);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}else{
    mysqli_set_charset($conexion, 'utf8');
    die("Conexión exitosa a la base de datos");
}

?>