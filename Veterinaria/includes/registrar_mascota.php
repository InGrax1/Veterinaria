<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $sexo = $_POST['sexo'];
    $id_propietario = $_SESSION['id_propietario'];

    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];

    $fecha_hora = $fecha . ' ' . $hora . ':00';
    $fechaHoraTimestamp = strtotime($fecha_hora);
    $ahora = time();

    if ($fechaHoraTimestamp < $ahora) {
        echo "<script>
            alert('No se puede agendar una cita en el pasado.');
            window.history.back();
        </script>";
        exit;
    }

    // Validar si la mascota ya está registrada
    $sqlCheckMascota = "SELECT * FROM mascota WHERE nombre = ? AND id_propietario = ?";
    $stmtCheckMascota = $conn->prepare($sqlCheckMascota);
    $stmtCheckMascota->bind_param("si", $nombre, $id_propietario);
    $stmtCheckMascota->execute();
    $resultMascota = $stmtCheckMascota->get_result();

    if ($resultMascota->num_rows > 0) {
        echo "<script>
            alert('Esta mascota ya está registrada.');
            window.history.back();
        </script>";
        exit;
    }

    // Insertar mascota
    $sqlMascota = "INSERT INTO mascota (nombre, edad, especie, raza, sexo, id_propietario)
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmtMascota = $conn->prepare($sqlMascota);
    $stmtMascota->bind_param("sisssi", $nombre, $edad, $especie, $raza, $sexo, $id_propietario);

    if ($stmtMascota->execute()) {
        $id_mascota = $stmtMascota->insert_id;

        // Validar si ya hay 2 citas en ese horario
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
            exit;
        }

        // Insertar cita médica
        $sqlCita = "INSERT INTO cita_medica (id_mascota, fecha_hora, motivo) VALUES (?, ?, ?)";
        $stmtCita = $conn->prepare($sqlCita);
        $stmtCita->bind_param("iss", $id_mascota, $fecha_hora, $motivo);

        if ($stmtCita->execute()) {
            echo "<script>
                alert('Mascota y cita médica registradas con éxito.');
                window.location.href='../inicio.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al guardar la cita: " . $conn->error . "');
                window.history.back();
            </script>";
        }

        $stmtCita->close();
        $stmtCheck->close();
    } else {
        echo "<script>
            alert('Error al guardar la mascota: " . $conn->error . "');
            window.history.back();
        </script>";
    }

    $stmtCheckMascota->close();
    $stmtMascota->close();
    $conn->close();
}
?>
