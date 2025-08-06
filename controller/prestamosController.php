<?php
require_once __DIR__ . '/../config/db.config.php';
require_once __DIR__ . '/../model/Prestamo.php';

$prestamos = new Prestamos($conexion);


?>