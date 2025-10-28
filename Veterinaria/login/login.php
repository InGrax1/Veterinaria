<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Usar consulta preparada para evitar inyección SQL
    $sql = "SELECT id_propietario, usuario, password FROM propietario WHERE usuario = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['id_propietario'] = $row['id_propietario']; // ✅ Guardamos el id en sesión

        header("Location: ../inicio.php");
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>