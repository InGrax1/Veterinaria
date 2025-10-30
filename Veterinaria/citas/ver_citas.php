<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/login.php';</script>";
    exit;
}

$id_propietario = $_SESSION['id_propietario'];

$sql = "SELECT c.id_cita, c.fecha_hora, c.motivo, m.nombre AS nombre_mascota, c.estado_atencion
        FROM cita_medica c
        INNER JOIN mascota m ON c.id_mascota = m.id_mascota
        WHERE m.id_propietario = ?
        ORDER BY c.fecha_hora DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_propietario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - Patitas Felices</title>
    <link rel="stylesheet" href="../css/ver_citas.css">
    
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

    <header class="site-header">
        <div class="container">
            <div class="logo">
                <span class="logo-icon">🐾</span>
                Patitas Felices
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="../inicio.php">Inicio</a></li>
                    <li><a href="../propietario/perfil.php">Perfil</a></li>
                    
                    <li class="dropdown">
                        <button class="dropbtn">Más Opciones</button>
                        <div class="dropdown-content">
                            <a href="regicita.php">Registrar Cita</a>
                            <a href="ver_citas.php">Ver Citas</a>
                            <a href="../historial/historial.php">Historial Clínico</a>
                            <a href="../contacto.php">Contacto</a>
                            <a href="../includes/logout.php" class="logout">Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="page-content">
        <div class="table-container">
            <h2>Mis Citas Médicas</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="table-wrapper">
                    <table class="citas-table">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Mascota</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['fecha_hora']) ?></td>
                                    <td><?= htmlspecialchars($row['nombre_mascota']) ?></td>
                                    <td><?= htmlspecialchars($row['motivo'] ?: 'Sin motivo') ?></td>
                                    <td>
                                        <?php 
                                        $estado = $row['estado_atencion'];
                                        // Usamos una clase para dar estilo al estado
                                        $clase_estado = 'estado-' . htmlspecialchars($estado);
                                        echo '<span class="estado-cita ' . $clase_estado . '">' . ucfirst($estado) . '</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($estado != 'atendida'): ?>
                                            <form method="POST" action="cancelar_cita.php" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?');">
                                                <input type="hidden" name="id_cita" value="<?= $row['id_cita'] ?>">
                                                <button type="submit" class="btn-cancelar">Cancelar</button>
                                            </form>
                                        <?php else: ?>
                                            <p class="ya-atendida">Cita Finalizada</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-citas-message">No tienes citas registradas.</p>
            <?php endif; ?>

            <a href="../inicio.php" class="btn btn-secondary">Regresar al inicio</a>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Clínica Veterinaria Patitas Felices © 2025</p>
            <p>Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
        </div>
    </footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>