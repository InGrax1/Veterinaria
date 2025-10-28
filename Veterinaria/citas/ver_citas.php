<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
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
<html>
<head>
    <title>Mis Citas</title>
    <link rel="stylesheet" href="../css/ver_citas.css">

  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <style>
        .ya-atendida {
            color: gray;
            font-style: italic;
        }
        .btn-cancelar {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <header class="header">
        <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices"class="logo">
        <h1>Clínica Veterinaria Patitas Felices</h1>

        <nav>
            <ul class="nav-menu">
                <li><a href="../inicio.php">Inicio</a></li>
                <li><a href="../propietario/perfil.php">Perfil</a></li>

                <li class="dropdown">
                    <button class="dropbtn">Más Opciones</button>
                    <div class="dropdown-content">
                        <a href="regicita.php">Registrar Cita</a>
                        <a href="#">Ver Citas</a>
                        <a href="../historial/historial.php">Historial Clínico</a>
                        <a href="../contacto.php">Contacto</a>
                        <a href="../includes/logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <br><br>
    
    <h1>Mis Citas Médicas</h1>

    <br><br><br>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Fecha y Hora</th>
                <th>Motivo</th>
                <th>Mascota</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fecha_hora'] ?></td>
                    <td><?= $row['motivo'] ?: 'Sin motivo' ?></td>
                    <td><?= $row['nombre_mascota'] ?></td>
                    <td>
                        <?php 
                        $estado = $row['estado_atencion'];
                        switch ($estado) {
                            case 'esperando':
                                echo 'Esperando';
                                break;
                            case 'atendiendo':
                                echo 'Atendiendo';
                                break;
                            case 'atendida':
                                echo 'Atendida';
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($estado != 'atendida'): ?>
                            <form method="POST" action="cancelar_cita.php" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?');">
                                <input type="hidden" name="id_cita" value="<?= $row['id_cita'] ?>">
                                <button type="submit" class="btn-cancelar">Cancelar</button>
                            </form>
                        <?php else: ?>
                            <p class="ya-atendida">Cita atendida</p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No tienes citas registradas.</p>
    <?php endif; ?>

    <br>
    <a href="../inicio.php">Regresar al inicio</a>

    <footer class="footer">
        <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
