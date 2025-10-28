<?php
session_start();
include '../includes/scriptdb.php';

// Verificar si el veterinario está logueado
if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

// Verificar que se recibió el ID de la cita
if (!isset($_GET['id_cita'])) {
    echo "ID de cita no proporcionado.";
    exit;
}

$id_cita = intval($_GET['id_cita']);

// Marcar la cita como "atendiendo"
$update = "UPDATE cita_medica SET estado_atencion = 'atendiendo' WHERE id_cita = $id_cita";
mysqli_query($conn, $update);

// Obtener datos de la cita, incluyendo el ID de la mascota
$query = "SELECT c.fecha_hora, c.motivo, m.nombre AS mascota, m.id_mascota, p.nombre AS propietario
          FROM cita_medica c
          JOIN mascota m ON c.id_mascota = m.id_mascota
          JOIN propietario p ON m.id_propietario = p.id_propietario
          WHERE c.id_cita = $id_cita";
$result = mysqli_query($conn, $query);
$cita = mysqli_fetch_assoc($result);

if (!$cita) {
    echo "Cita no encontrada.";
    exit;
}

$id_mascota = $cita['id_mascota'];

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];
    $notas = $_POST['notas'];
    $recetas = $_POST['recetas'];
    $tipo_vacuna = $_POST['tipo_vacuna'];
    $fecha_aplicacion = $_POST['fecha_aplicacion'];

    // Insertar historial clínico
    $sql1 = "INSERT INTO historial_clinico (id_mascota, diagnostico, tratamiento, notas, recetas)
             VALUES (?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("issss", $id_mascota, $diagnostico, $tratamiento, $notas, $recetas);
    $stmt1->execute();

    // Insertar vacuna si se ingresó
    if (!empty($tipo_vacuna) && !empty($fecha_aplicacion)) {
        $sql2 = "INSERT INTO cartilla_vacunacion (id_mascota, tipo_vacuna, fecha_aplicacion)
                 VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iss", $id_mascota, $tipo_vacuna, $fecha_aplicacion);
        $stmt2->execute();
    }

    echo "<p style='color: green;'>Atención guardada con éxito.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Atender Paciente</title>
    <link rel="stylesheet" href="../css/estiloatender.css">
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>

</head>
<body>
<div class="container">

    <div class="card">
        <h2>Atendiendo a <?= htmlspecialchars($cita['mascota']) ?></h2>
        <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($cita['fecha_hora'])) ?> a las <?= date('H:i', strtotime($cita['fecha_hora'])) ?></p>
        <p><strong>Propietario:</strong> <?= htmlspecialchars($cita['propietario']) ?></p>
        <p><strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo']) ?></p>

        <div class="action-bar">
            <a href="crear_receta.php?id_cita=<?= $id_cita ?>" class="action-button">Crear Receta</a>
            <a href="agendar_siguiente.php?id_cita=<?= $id_cita ?>" class="action-button">Agendar Siguiente Cita</a>
            <a href="finalizar_atencion.php?id_cita=<?= $id_cita ?>" class="action-button finalizar">Finalizar Atención</a>
        </div>
        <a href="pacientes_hoy.php" class="back-to-list">← Volver a la lista</a>
    </div>

    <div class="card">
        <h3>Historial Clínico</h3>

        <?php if (isset($mensaje)): ?>
            <p class="success-message"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Diagnóstico:</label>
            <textarea name="diagnostico" required></textarea>

            <label>Tratamiento:</label>
            <textarea name="tratamiento" required></textarea>

            <label>Notas:</label>
            <textarea name="notas"></textarea>

            <label>Recetas:</label>
            <textarea name="recetas"></textarea>

            <h3>Registrar Vacuna (opcional)</h3>

            <label>Tipo de vacuna:</label>
            <input type="text" name="tipo_vacuna">

            <label>Fecha de aplicación:</label>
            <input type="date" name="fecha_aplicacion">

            <button type="submit">Guardar atención</button>
        </form>
    </div>

</div>
</body>
</html>