<?php
session_start();

// Duracipon de sesion de 1 hora papa
$tiempo_inactividad = 3600; 

if (!isset($_SESSION['usuario'])) {
    header("Location: /Veterinaria/inicio.php");
    exit();
}

// Control de inactividad
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $tiempo_inactividad)) {
    session_unset();
    session_destroy();
    header("Location: /Veterinaria/inicio.php?timeout=1");
    exit();
}

$_SESSION['last_activity'] = time();
?>