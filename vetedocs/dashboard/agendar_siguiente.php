<?php
include '../includes/scriptdb.php';
session_start();

if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

$id_cita = $_GET['id_cita'] ?? null;

// Obtener datos de la cita original
$query = "SELECT * FROM cita_medica WHERE id_cita = $id_cita";
$result = mysqli_query($conn, $query);
$cita = mysqli_fetch_assoc($result);

if (!$cita) {
    echo "Cita no encontrada.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];

    $id_mascota = $cita['id_mascota'];
    $id_veterinario = $_SESSION['veterinario_id'];
    $fecha_hora = "$fecha $hora";

    $query = "INSERT INTO cita_medica (id_mascota, id_veterinario, fecha_hora, motivo)
              VALUES ($id_mascota, $id_veterinario, '$fecha_hora', '$motivo')";
    mysqli_query($conn, $query);

    header("Location: atender.php?id_cita=$id_cita");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar Siguiente Cita</title>
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
        input[type="date"],
        input[type="time"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-top: 5px;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
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
    <h2>Agendar Siguiente Cita para Mascota #<?= $cita['id_mascota'] ?></h2>
    <form method="POST">
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required>

        <label for="hora">Hora:</label>
        <input type="time" name="hora" required>

        <label for="motivo">Motivo:</label>
        <input type="text" name="motivo" required>

        <button type="submit">Agendar</button>
    </form>
    <a href="atender.php?id_cita=<?= $id_cita ?>" class="back-to-list">← Volver</a>
</div>

</body>
</html>
