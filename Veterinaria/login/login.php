<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    
    // Variable para determinar si se encontró el usuario
    $usuario_encontrado = false;
    $tipo_usuario = '';

    // PRIMERO: Intentar login como PROPIETARIO
    $sql_propietario = "SELECT id_propietario, usuario, password FROM propietario WHERE usuario = ? AND password = ?";
    $stmt_propietario = $conn->prepare($sql_propietario);
    $stmt_propietario->bind_param("ss", $usuario, $password);
    $stmt_propietario->execute();
    $result_propietario = $stmt_propietario->get_result();

    if ($result_propietario->num_rows === 1) {
        // ES UN PROPIETARIO (CLIENTE)
        $row = $result_propietario->fetch_assoc();
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['id_propietario'] = $row['id_propietario'];
        $_SESSION['tipo_usuario'] = 'propietario';
        
        $usuario_encontrado = true;
        $tipo_usuario = 'propietario';
    }
    $stmt_propietario->close();

    // SEGUNDO: Si no es propietario, intentar login como VETERINARIO
    if (!$usuario_encontrado) {
        $sql_veterinario = "SELECT id_veterinario, nombre, usuario, password FROM veterinario WHERE usuario = ? AND password = ?";
        $stmt_veterinario = $conn->prepare($sql_veterinario);
        $stmt_veterinario->bind_param("ss", $usuario, $password);
        $stmt_veterinario->execute();
        $result_veterinario = $stmt_veterinario->get_result();

        if ($result_veterinario->num_rows === 1) {
            // ES UN VETERINARIO (DOCTOR)
            $row = $result_veterinario->fetch_assoc();
            $_SESSION['veterinario_id'] = $row['id_veterinario'];
            $_SESSION['veterinario_nombre'] = $row['nombre'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['tipo_usuario'] = 'veterinario';
            
            $usuario_encontrado = true;
            $tipo_usuario = 'veterinario';
        }
        $stmt_veterinario->close();
    }

    // REDIRIGIR según el tipo de usuario
    if ($usuario_encontrado) {
        if ($tipo_usuario === 'propietario') {
            // Redirigir a la interfaz de cliente
            header("Location: ../inicio.php");
            exit();
        } elseif ($tipo_usuario === 'veterinario') {
            // Redirigir a la interfaz de veterinario
            header("Location: ../../vetedocs/dashboard/home_vet.php");
            exit();
        }
    } else {
        // Usuario o contraseña incorrectos
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.php';</script>";
    }

    $conn->close();
}
?>

<link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
<link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>