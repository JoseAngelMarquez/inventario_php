<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();    
session_destroy(); 

require_once __DIR__ . '/../views/login.php';
exit();
