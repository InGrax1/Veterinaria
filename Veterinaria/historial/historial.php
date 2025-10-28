<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('No ha iniciado sesión.'); window.location.href='../login/index.php';</script>";
    exit;
}

$id_propietario = $_SESSION['id_propietario'];

// Consulta de historial clínico, recetas y cartillas de vacunación
$query = "
    SELECT 
        h.id_historial,
        h.diagnostico,
        h.tratamiento,
        h.notas,
        h.recetas,
        NULL AS medicamentos,
        NULL AS receta_notas,
        NULL AS fecha,
        m.nombre AS nombre_mascota,
        'historial' AS tipo
    FROM historial_clinico h
    JOIN mascota m ON m.id_mascota = h.id_mascota
    WHERE m.id_propietario = ?

    UNION ALL

    SELECT 
        r.id_receta AS id_historial,
        r.diagnostico,
        r.tratamiento,
        r.notas,
        NULL AS recetas,
        r.medicamentos,
        r.notas AS receta_notas,
        r.fecha,
        m.nombre AS nombre_mascota,
        'receta' AS tipo
    FROM receta r
    JOIN cita_medica c ON r.id_cita = c.id_cita
    JOIN mascota m ON c.id_mascota = m.id_mascota
    WHERE m.id_propietario = ?
    
    UNION ALL

    SELECT 
        v.id_cartilla AS id_historial,
        v.tipo_vacuna AS diagnostico,
        NULL AS tratamiento,
        NULL AS notas,
        NULL AS recetas,
        NULL AS medicamentos,
        NULL AS receta_notas,
        v.fecha_aplicacion AS fecha,
        m.nombre AS nombre_mascota,
        'vacuna' AS tipo
    FROM cartilla_vacunacion v
    JOIN mascota m ON v.id_mascota = m.id_mascota
    WHERE m.id_propietario = ?
    ORDER BY fecha DESC, id_historial DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $id_propietario, $id_propietario, $id_propietario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico y Recetas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/histo.css">
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

<header class="header">
    <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
    <h1>Clínica Veterinaria Patitas Felices</h1>
    <nav>
        <ul class="nav-menu">
            <li><a href="../inicio.php">Inicio</a></li>
            <li><a href="../propietario/perfil.php">Perfil</a></li>
            <li class="dropdown">
                <button class="dropbtn">Más Opciones</button>
                <div class="dropdown-content">
                    <a href="../citas/regicita.php">Registrar Cita</a>
                    <a href="../citas/ver_citas.php">Ver Citas</a>
                    <a href="#">Historial Clínico</a>
                    <a href="../contacto.php">Contacto</a>
                    <a href="../includes/logout.php">Cerrar sesión</a>
                </div>
            </li>
        </ul>
    </nav>
</header>

<h2>Historial Clínico y Recetas de tus Mascotas</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="historial">
        <h3>Nombre de la mascota: <?php echo htmlspecialchars($row['nombre_mascota']); ?></h3>
        <?php if ($row['tipo'] === 'historial'): ?>
            <h4>Historial Clínico</h4>
            <p><strong>Diagnóstico:</strong> <?php echo htmlspecialchars($row['diagnostico']); ?></p>
            <p><strong>Tratamiento:</strong> <?php echo htmlspecialchars($row['tratamiento']); ?></p>
            <p><strong>Notas:</strong> <?php echo htmlspecialchars($row['notas']); ?></p>
            <p><strong>Recetas asociadas:</strong> <?php echo htmlspecialchars($row['recetas']); ?></p>
        <?php elseif ($row['tipo'] === 'receta'): ?>
            <h4>Receta Médica</h4>
            <p><strong>Fecha:</strong> <?php echo date("d/m/Y H:i", strtotime($row['fecha'])); ?></p>
            <p><strong>Diagnóstico:</strong> <?php echo htmlspecialchars($row['diagnostico']); ?></p>
            <p><strong>Tratamiento:</strong> <?php echo htmlspecialchars($row['tratamiento']); ?></p>
            <p><strong>Notas:</strong> <?php echo htmlspecialchars($row['receta_notas']); ?></p>
            <p><strong>Medicamentos:</strong> <?php echo htmlspecialchars($row['medicamentos']); ?></p>
        <?php else: ?>
            <h4>Cartilla de Vacunación</h4>
            <p><strong>Tipo de Vacuna:</strong> <?php echo htmlspecialchars($row['diagnostico']); ?></p>
            <p><strong>Fecha de Aplicación:</strong> <?php echo date("d/m/Y", strtotime($row['fecha'])); ?></p>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

<footer class="footer">
    <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
</footer>

</body>
</html>
