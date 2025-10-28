<?php
session_start();
include '../includes/scriptdb.php';  // Asegúrate de que el archivo de conexión esté correctamente incluido

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $sql = "SELECT * FROM veterinario WHERE correo_electronico = '$correo'";
    $res = mysqli_query($conn, $sql);  // Cambia $conexion por $conn
    $vet = mysqli_fetch_assoc($res);

    if ($vet) {
        $_SESSION['veterinario_id'] = $vet['id_veterinario'];
        $_SESSION['veterinario_nombre'] = $vet['nombre'];
        // Redirigir a la página de validación para confirmar sesión activa
        header("Location: validar_vet.php");
        exit;
    } else {
        $error = "Veterinario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Veterinario</title>
    <link rel="stylesheet" href="../css/estilo_login.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>


    <div class="login-container">
        <img class="logo" src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Veterinaria">
        <h2>Iniciar sesión - Veterinario</h2>
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" required><br>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>