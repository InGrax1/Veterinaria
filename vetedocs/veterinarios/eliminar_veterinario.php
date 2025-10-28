<?php
include '../includes/scriptdb.php';
session_start();

// Verificar permisos de acceso
if (!in_array($_SESSION['veterinario_id'], [1, 2, 3])) {
    echo "<p style='color:red'>No tienes permisos para acceder a esta sección.</p>";
    exit;
}

// Obtener el ID del veterinario desde la URL
$id_veterinario = $_GET['id'];

// Verificar si el ID del veterinario existe
$query = "SELECT * FROM veterinario WHERE id_veterinario = $id_veterinario";
$resultado = mysqli_query($conn, $query);
$veterinario = mysqli_fetch_assoc($resultado);

if (!$veterinario) {
    echo "<p>No se encontró el veterinario.</p>";
    exit;
}

// Eliminar al veterinario de la base de datos
$delete_query = "DELETE FROM veterinario WHERE id_veterinario = $id_veterinario";
if (mysqli_query($conn, $delete_query)) {
    echo "<p>Veterinario eliminado correctamente.</p>";
    header("Location: veterinarios.php"); // Redirige de vuelta a la lista de veterinarios
} else {
    echo "<p style='color:red'>Error al eliminar: " . mysqli_error($conn) . "</p>";
}
?>
