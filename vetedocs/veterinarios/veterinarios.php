<?php
// Incluir la conexión a la base de datos
include '../includes/scriptdb.php';
session_start();

// Consulta SQL para obtener todos los veterinarios registrados
$resultado = mysqli_query($conn, "SELECT * FROM veterinario");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinarios Registrados</title>
    <link rel="stylesheet" href="../css/estilo_vet.css">
    <link rel="stylesheet" href="../css/estivets.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

<h2>Veterinarios Registrados</h2>

<!-- Botón para registrar un nuevo veterinario (visible solo para los primeros tres veterinarios) -->
<?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])) { ?>
    <a href="registrar_veterinario.php" class="add-vet-button">Registrar Nuevo Veterinario</a>
<?php } ?>

<!-- Botón para regresar al inicio -->
<a href="../dashboard/home_vet.php" class="add-vet-button">Volver al inicio</a>

<!-- Tabla que muestra los veterinarios -->
<table>
    <tr>
        <th>Nombre</th>
        <th>Especialidad</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th>Acciones</th>
    </tr>

    <!-- Mostrar los veterinarios registrados -->
    <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
    <tr>
        <td><?= $row['nombre'] ?></td>
        <td><?= $row['especialidad'] ?></td>
        <td><?= $row['correo_electronico'] ?></td>
        <td><?= $row['telefono'] ?></td>
        <td>
            <!-- Enlace para editar cada veterinario solo si el usuario tiene permisos -->
            <?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])) { ?>
                <a href="editar_veterinario.php?id=<?= $row['id_veterinario'] ?>">Editar</a> |
                <a href="eliminar_veterinario.php?id=<?= $row['id_veterinario'] ?>" 
                   onclick="return confirm('¿Estás seguro de que quieres eliminar a este veterinario?');">Eliminar</a>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>



</body>
</html>