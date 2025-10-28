<?php
// Conectar a la base de datos
include '../includes/scriptdb.php';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    

    // checa si las contaselas coinciden
    if ($password !== $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden'); window.location.href='registro.php';</script>";
        exit;
    }



    // ve si el usuario ya esta
    $sql = "SELECT * FROM propietario WHERE usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('El usuario ya está registrado'); window.location.href='registro.php';</script>";
        exit;
    }

    // guaerda los datos en la tabla veterinaria 
    $sql_insert = "INSERT INTO propietario (nombre, direccion, telefono, correo, usuario, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssssss", $nombre, $direccion, $telefono, $correo, $usuario, $password);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href='../login/index.php';</script>";
        exit;
    } else {
        die("Error al registrar el usuario: " . $stmt_insert->error);
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Propietario - Patitas Felices</title>
    <link rel="stylesheet" href="../css/regis.css">

  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

    <form method="POST" action="registro.php">
    <div class="background-animation"></div> <!-- Animación de fondo -->
    <div class="register-container">

        <img 
        src="https://media.tenor.com/X1nlfLKP6toAAAAM/cat-eat.gif" 
        alt="Gatito" 
        width="75" height="75">

        <h2>Registro de Propietario</h2>

        <input type="text" name="nombre" placeholder="Nombre" class="input" required>
        <input type="text" name="usuario" placeholder="Nombre de usuario" class="input" required>
        <input type="email" name="correo" placeholder="Correo electrónico" class="input" required>
        <input type="password" name="password" placeholder="Contraseña" class="input" required>
        <input type="password" name="confirm_password" placeholder="Confirmar contraseña" class="input" required>
        <input type="text" name="direccion" placeholder="Dirección" class="input" required>
        <input type="text" name="telefono" placeholder="Teléfono" class="input" required>

            <button class="btn">Crear Cuenta</button>
            <p class="sign-up">¿Ya tienes una cuenta? <a href="../login/index.php">Inicia sesión aquí</a></p>

</form>

        
    </div>
    <script src="../js/script.js"></script>

    
</body>
</html>
