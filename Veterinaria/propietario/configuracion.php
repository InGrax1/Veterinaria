<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
    exit;
}

$id_propietario = $_SESSION['id_propietario'];

$sql = "SELECT correo, telefono FROM propietario WHERE id_propietario = $id_propietario";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Configuración</title>
  <link rel="stylesheet" href="../css/configuracion.css">
</head>
<body>
  <h2>Configuración de la Cuenta</h2>
  <form action="actualizar_configuracion.php" method="POST">
    <label>Nuevo correo electrónico:</label><br>
    <input type="email" name="correo" value="<?php echo $data['correo']; ?>"><br>

    <label>Nuevo teléfono:</label><br>
    <input type="text" name="telefono" value="<?php echo $data['telefono']; ?>"><br>

    <label>Cambiar contraseña:</label><br>
    <input type="password" name="password" placeholder="Nueva contraseña"><br>

    <label>Notificaciones por correo:</label><br>
    <input type="checkbox" name="notificaciones" checked disabled><br> <!-- Simulado -->

    <h3>Idioma</h3>
    <p>Próximamente...</p>

    <input type="submit" value="Guardar Cambios">
  </form>
</body>
</html>
