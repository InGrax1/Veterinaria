<?php
include '../includes/scriptdb.php';
session_start();

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
    exit;
}

$id_propietario = $_SESSION['id_propietario'];

$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$password = $_POST['password'];

if (!empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE propietario SET correo='$correo', telefono='$telefono', password='$password' WHERE id_propietario = $id_propietario";
} else {
    $sql = "UPDATE propietario SET correo='$correo', telefono='$telefono' WHERE id_propietario = $id_propietario";
}

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Datos actualizados correctamente.'); window.location.href='configuracion.php';</script>";
} else {
    echo "<script>alert('Error al actualizar.'); window.location.href='configuracion.php';</script>";
}

exit;
