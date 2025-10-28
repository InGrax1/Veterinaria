<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_propietario'])) {
        echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
        exit;
    }

    $id_cita = $_POST['id_cita'];
    $id_propietario = $_SESSION['id_propietario'];

    // Verificar que la cita pertenece a este propietario
    $sql = "SELECT c.id_cita
            FROM cita_medica c
            JOIN mascota m ON c.id_mascota = m.id_mascota
            WHERE c.id_cita = ? AND m.id_propietario = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_cita, $id_propietario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Eliminar la cita
        $deleteSql = "DELETE FROM cita_medica WHERE id_cita = ?";
        $stmtDelete = $conn->prepare($deleteSql);
        $stmtDelete->bind_param("i", $id_cita);

        if ($stmtDelete->execute()) {
            echo "<script>alert('Cita cancelada exitosamente.'); window.location.href='ver_citas.php';</script>";
        } else {
            echo "<script>alert('Error al cancelar la cita.'); window.location.href='ver_citas.php';</script>";
        }

        $stmtDelete->close();
    } else {
        echo "<script>alert('No tienes permiso para cancelar esta cita.'); window.location.href='ver_citas.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ver_citas.php");
    exit;
}
