<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mascota = $_POST['mascota_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];

    $fecha_hora = $fecha . ' ' . $hora . ':00';

    // Validar que no sea una fecha pasada
    $fechaHoraTimestamp = strtotime($fecha_hora);
    $ahora = time();

    if ($fechaHoraTimestamp < $ahora) {
        echo "<script>
            alert('No se puede agendar una cita en el pasado.');
            window.history.back();
        </script>";
        exit;
    }

    // Validar máximo 2 citas por horario
    $sqlCheck = "SELECT COUNT(*) AS total FROM cita_medica WHERE fecha_hora = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $fecha_hora);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $data = $resultCheck->fetch_assoc();

    if ($data['total'] >= 2) {
        echo "<script>
            alert('Ya hay 2 citas en ese horario. Por favor elige otro.');
            window.history.back();
        </script>";
        $stmtCheck->close();
        $conn->close();
        exit;
    }

    // Registrar cita
    $sql = "INSERT INTO cita_medica (id_mascota, fecha_hora, motivo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id_mascota, $fecha_hora, $motivo);

    if ($stmt->execute()) {
        echo "<script>
            alert('Cita registrada correctamente.');
            window.location.href='../inicio.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar la cita: " . $conn->error . "');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $stmtCheck->close();
    $conn->close();
}
?>
