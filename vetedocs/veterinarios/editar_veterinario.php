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

// Consultar el veterinario para editar
$query = "SELECT * FROM veterinario WHERE id_veterinario = $id_veterinario";
$resultado = mysqli_query($conn, $query);
$veterinario = mysqli_fetch_assoc($resultado);

// Si no se encuentra el veterinario
if (!$veterinario) {
    echo "<p>No se encontró el veterinario.</p>";
    exit;
}

// Procesar el formulario para editar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $especialidad = $_POST['especialidad'];

    // Actualizar la base de datos
    $update_query = "UPDATE veterinario SET nombre='$nombre', telefono='$telefono', correo_electronico='$correo', especialidad='$especialidad' WHERE id_veterinario = $id_veterinario";

    if (mysqli_query($conn, $update_query)) {
        echo "<p>Veterinario actualizado con éxito.</p>";
    } else {
        echo "<p style='color:red'>Error al actualizar: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Veterinario</title>
    <link rel="stylesheet" href="../css/estivets.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

<h2>Editar Veterinario</h2>

<form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= $veterinario['nombre'] ?>" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= $veterinario['telefono'] ?>" required>

    <label>Correo:</label>
    <input type="email" name="correo" value="<?= $veterinario['correo_electronico'] ?>" required>

    <label>Especialidad:</label>
    <input type="text" name="especialidad" value="<?= $veterinario['especialidad'] ?>" required>



    <input type="submit" class="buttons" value="Actualizar Veterinario">
</form>

<br><br>
<a href="veterinarios.php" class="add-vet-button">Volver a la lista</a>

</body>
</html>
