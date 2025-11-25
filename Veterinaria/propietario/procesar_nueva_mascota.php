<?php
session_start();
include '../includes/scriptdb.php';

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_propietario = $_SESSION['id_propietario'];
    
    // Recibir datos del formulario
    $nombre = trim($_POST['nombre']);
    $edad = intval($_POST['edad']);
    $especie = trim($_POST['especie']);
    $raza = trim($_POST['raza']);
    $sexo = $_POST['sexo'];
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    
    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($especie) || empty($raza) || empty($sexo)) {
        echo "<script>alert('Por favor, completa todos los campos obligatorios.'); history.back();</script>";
        exit;
    }
    
    // Insertar la nueva mascota en la base de datos
    $sql = "INSERT INTO mascota (id_propietario, nombre, edad, especie, raza, sexo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isisss", $id_propietario, $nombre, $edad, $especie, $raza, $sexo);
    
    if ($stmt->execute()) {
        $id_mascota_nueva = $conn->insert_id;
        
        echo "<script>
            alert('¡Mascota registrada exitosamente! 🐾');
            window.location.href='perfil.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar la mascota. Por favor, intenta nuevamente.');
            history.back();
        </script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: perfil.php");
    exit;
}
?>