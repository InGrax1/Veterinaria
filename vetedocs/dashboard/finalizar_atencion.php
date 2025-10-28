<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

if (!isset($_GET['id_cita'])) {
    echo "ID de cita no proporcionado.";
    exit;
}

$id_cita = intval($_GET['id_cita']);

$query = "UPDATE cita_medica SET estado_atencion = 'atendida' WHERE id_cita = $id_cita";

if (mysqli_query($conn, $query)) {
    header("Location: pacientes_hoy.php?vista=hoy");
    exit;
} else {
    echo "Error al finalizar la atención: " . mysqli_error($conn);
}
