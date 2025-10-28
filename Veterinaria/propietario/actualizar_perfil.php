<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('No ha iniciado sesión.'); window.location.href='../login/index.php';</script>";
    exit;
}

$id = $_SESSION['id_propietario'];

// Actualizar datos del propietario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE propietario SET nombre='$nombre', correo='$correo', telefono='$telefono', direccion='$direccion', usuario='$usuario', password='$password_hash' WHERE id_propietario=$id";
} else {
    $query = "UPDATE propietario SET nombre='$nombre', correo='$correo', telefono='$telefono', direccion='$direccion', usuario='$usuario' WHERE id_propietario=$id";
}
mysqli_query($conn, $query);

// Actualizar mascotas
if (isset($_POST['mascotas']) && is_array($_POST['mascotas'])) {
    foreach ($_POST['mascotas'] as $mascota) {
        $id_mascota = $mascota['id_mascota'];
        $nombre = $mascota['nombre'];
        $edad = $mascota['edad'];
        $especie = $mascota['especie'];
        $raza = $mascota['raza'];
        $sexo = $mascota['sexo'];

        $sql = "UPDATE mascota SET nombre='$nombre', edad='$edad', especie='$especie', raza='$raza', sexo='$sexo' WHERE id_mascota=$id_mascota AND id_propietario=$id";
        mysqli_query($conn, $sql);
    }
}

echo "<script>alert('Perfil actualizado exitosamente.'); window.location.href='perfil.php';</script>";
?>
