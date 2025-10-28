<?php
session_start();

// Inicializar $mensaje
$mensaje = "";

if (!in_array($_SESSION['veterinario_id'], [1, 2, 3])) {
    echo "<div style='text-align:center; background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 8px; margin: 50px auto; width: 80%; max-width: 400px;'>
            <strong>¡Acceso denegado!</strong> No tienes permisos para acceder a esta sección.
          </div>";
    exit;
}

include '../includes/scriptdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $especialidad = $_POST['especialidad'];

    // Verificar si el correo ya existe
    $verificarCorreo = mysqli_query($conn, "SELECT * FROM veterinario WHERE correo_electronico = '$correo'");
    // Verificar si el teléfono ya existe
    $verificarTelefono = mysqli_query($conn, "SELECT * FROM veterinario WHERE telefono = '$telefono'");

    if (mysqli_num_rows($verificarCorreo) > 0) {
        $mensaje = "❌ Ya existe un veterinario registrado con ese correo electrónico.";
    } elseif (mysqli_num_rows($verificarTelefono) > 0) {
        $mensaje = "❌ Ya existe un veterinario registrado con ese número de teléfono.";
    } else {
        $sql = "INSERT INTO veterinario (nombre, telefono, correo_electronico, especialidad)
                VALUES ('$nombre', '$telefono', '$correo', '$especialidad')";

        if (mysqli_query($conn, $sql)) {
            $mensaje = "✅ Veterinario registrado correctamente.";
        } else {
            $mensaje = "❌ Error al registrar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Veterinario</title>
    <link rel="stylesheet" href="css/estilo_vet.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        form { background: white; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; }
        input, select {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745; color: white; border: none; cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .mensaje {
            text-align: center; margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Registrar Nuevo Veterinario</h2>

<form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" required>

    <label>Correo electrónico:</label>
    <input type="email" name="correo" required>

    <label>Especialidad:</label>
    <input type="text" name="especialidad" required>

    <input type="submit" value="Registrar Veterinario">
</form>

<div class="mensaje">
    <?php echo $mensaje; ?>
</div>

<!-- Botón para regresar al inicio -->
<div style="text-align: center; margin-top: 20px;">
    <a href="../dashboard/home_vet.php" style="text-decoration: none; background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px;">Regresar al Inicio</a>
</div>

</body>
</html>
