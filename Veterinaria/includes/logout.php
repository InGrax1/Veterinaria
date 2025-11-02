<?php
session_start();

// Guardar el tipo de usuario antes de destruir la sesión
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir según el tipo de usuario que era
if ($tipo_usuario === 'veterinario') {
    // Veterinarios van a su login
    header("Location: ../inicio.php");
} else {
    // Propietarios y visitantes van al login general
    header("Location: ../inicio.php");
}
exit;
?>