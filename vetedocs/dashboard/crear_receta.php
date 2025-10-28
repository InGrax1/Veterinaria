<?php
include '../includes/scriptdb.php';
session_start();

if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

$id_cita = $_GET['id_cita'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];
    $medicamentos = $_POST['medicamentos'];
    $notas = $_POST['contenido']; // Campo del textarea

    // Consulta para insertar todos los datos
    $query = "INSERT INTO receta (id_cita, diagnostico, tratamiento, notas, medicamentos, fecha) 
              VALUES ($id_cita, '$diagnostico', '$tratamiento', '$notas', '$medicamentos', NOW())";

    // Ejecutar la consulta
    if (mysqli_query($conn, $query)) {
        header("Location: atender.php?id_cita=$id_cita");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Receta</title>
    <link rel="stylesheet" href="../css/estilo_vet.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <style>
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            color: #444;
        }
        textarea {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            resize: vertical;
            font-size: 1rem;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }
        .back-to-list {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Crear Receta Médica</h2>
    <form method="POST">
        <label for="diagnostico">Diagnóstico:</label><br>
        <textarea name="diagnostico" rows="4" required></textarea><br>

        <label for="tratamiento">Tratamiento:</label><br>
        <textarea name="tratamiento" rows="4" required></textarea><br>

        <label for="medicamentos">Medicamentos:</label><br>
        <textarea name="medicamentos" rows="4" required></textarea><br>

        <label for="contenido">Contenido de la receta (Notas):</label><br>
        <textarea name="contenido" rows="10" required></textarea><br>

        <button type="submit">Guardar Receta</button>
    </form>
    <a href="atender.php?id_cita=<?= $id_cita ?>" class="back-to-list">← Volver</a>
</div>

</body>
</html>
