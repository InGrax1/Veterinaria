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
    <title>Registro - Clínica VetCare</title>
    
    <link rel="stylesheet" href="../css/regis.css">
    
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

    <div class="register-container">
        
        <div class="register-image-panel">
            </div>

        <div class="register-form-panel">
            
            <div class="logo">
                <span class="logo-icon">🐾</span>
                Clínica Patitas Felices
            </div>

            <h1>Crea tu cuenta</h1>
            <p class="subtitle">Ingresa tus datos para registrarte</p>

            <form method="POST" action="registro.php" class="form-grid">
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre" required>
                </div>

                <div class="form-group">
                    <label for="usuario">Nombre de usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="tu_usuario" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="tu@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ej. 55 1234 5678" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-group span-two">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Calle, Número, Colonia, C.P." required>
                </div>

                <button type="submit" class="btn-register span-two">Crear Cuenta</button>
            
            </form>

            <p class="login-link">
                ¿Ya tienes una cuenta? <a href="../login/#">Inicia Sesión</a>
            </p>
        </div>
    </div>

</body>
</html>