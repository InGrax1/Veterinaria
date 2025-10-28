<?php
session_start();

// Verificar si ya está logueado
if (!isset($_SESSION['veterinario_id'])) {
    // Si no está logueado, redirigir al formulario de inicio de sesión
    header("Location: index.php");
    exit;
} else {
    // Si está logueado, redirigir a la página principal
    header("Location: ../dashboard/home_vet.php");
    exit;
}
