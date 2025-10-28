<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

$vista = $_GET['vista'] ?? 'hoy';
$fecha_actual = date('Y-m-d');

switch ($vista) {
    case 'proximos':
        $fecha_fin = date('Y-m-d', strtotime('+7 days'));
        $titulo = "Próximos 7 días";
        $query = "SELECT c.id_cita, c.fecha_hora, c.estado_atencion, c.motivo,
                         m.nombre AS nombre_mascota, m.especie, m.raza,
                         p.nombre AS nombre_propietario
                  FROM cita_medica c
                  JOIN mascota m ON c.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  WHERE DATE(c.fecha_hora) > CURDATE() AND DATE(c.fecha_hora) <= '$fecha_fin'
                  ORDER BY c.fecha_hora";
        break;

    case 'anteriores':
        $fecha_inicio = date('Y-m-d', strtotime('-7 days'));
        $titulo = "Últimos 7 días";
        $query = "SELECT c.id_cita, c.fecha_hora, c.estado_atencion, c.motivo,
                         m.nombre AS nombre_mascota, m.especie, m.raza,
                         p.nombre AS nombre_propietario
                  FROM cita_medica c
                  JOIN mascota m ON c.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  WHERE DATE(c.fecha_hora) >= '$fecha_inicio' AND DATE(c.fecha_hora) < CURDATE()
                  ORDER BY c.fecha_hora DESC";
        break;

    case 'mes_actual':
        $inicio_mes = date('Y-m-01');
        $titulo = "Pacientes del mes actual";
        $query = "SELECT c.id_cita, c.fecha_hora, c.estado_atencion, c.motivo,
                         m.nombre AS nombre_mascota, m.especie, m.raza,
                         p.nombre AS nombre_propietario
                  FROM cita_medica c
                  JOIN mascota m ON c.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  WHERE DATE(c.fecha_hora) >= '$inicio_mes' AND DATE(c.fecha_hora) <= CURDATE()
                  ORDER BY c.fecha_hora DESC";
        break;

    case 'mes_pasado':
        $inicio_mes_pasado = date('Y-m-01', strtotime('-1 month'));
        $fin_mes_pasado = date('Y-m-t', strtotime('-1 month'));
        $titulo = "Pacientes del mes pasado";
        $query = "SELECT c.id_cita, c.fecha_hora, c.estado_atencion, c.motivo,
                         m.nombre AS nombre_mascota, m.especie, m.raza,
                         p.nombre AS nombre_propietario
                  FROM cita_medica c
                  JOIN mascota m ON c.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  WHERE DATE(c.fecha_hora) BETWEEN '$inicio_mes_pasado' AND '$fin_mes_pasado'
                  ORDER BY c.fecha_hora DESC";
        break;

    case 'hoy':
    default:
        $titulo = "Pacientes de Hoy";
        $query = "SELECT c.id_cita, c.fecha_hora, c.motivo, c.estado_atencion,
                         m.nombre AS nombre_mascota, m.especie, m.raza,
                         p.nombre AS nombre_propietario
                  FROM cita_medica c
                  JOIN mascota m ON c.id_mascota = m.id_mascota
                  JOIN propietario p ON m.id_propietario = p.id_propietario
                  WHERE DATE(c.fecha_hora) = CURDATE()
                  ORDER BY c.fecha_hora";
        break;
}

$resultado = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link rel="stylesheet" href="../css/pacientes_hoy.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

<header class="header">
    <div class="header-left">
        <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
        <div>
            <h1>Clínica Veterinaria Patitas Felices</h1>
            <h2><?= $titulo ?></h2>
        </div>
    </div>

    <ul class="nav-menu">
        <li><a href="home_vet.php">Inicio</a></li>
    </ul>
</header>

<nav class="tabs">
    <a href="?vista=hoy" class="<?= $vista === 'hoy' ? 'active' : '' ?>">Hoy</a>
    <a href="?vista=proximos" class="<?= $vista === 'proximos' ? 'active' : '' ?>">Próximos 7 días</a>
    <a href="?vista=anteriores" class="<?= $vista === 'anteriores' ? 'active' : '' ?>">Últimos 7 días</a>
    <a href="?vista=mes_actual" class="<?= $vista === 'mes_actual' ? 'active' : '' ?>">Mes Actual</a>
    <a href="?vista=mes_pasado" class="<?= $vista === 'mes_pasado' ? 'active' : '' ?>">Mes Pasado</a>
</nav>

<main class="contenido">
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Nombre Mascota</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Propietario</th>
                <?php if ($vista === 'hoy') echo "<th>Motivo</th><th>Acción</th>"; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?= date('H:i', strtotime($row['fecha_hora'])) ?></td>
                    <td><?= htmlspecialchars($row['nombre_mascota']) ?></td>
                    <td><?= htmlspecialchars($row['especie']) ?></td>
                    <td><?= htmlspecialchars($row['raza']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_propietario']) ?></td>
                    <?php if ($vista === 'hoy') { ?>
                        <td><?= htmlspecialchars($row['motivo']) ?></td>
                        <td>
                            <?php if ($row['estado_atencion'] === 'esperando'): ?>
                                <a class="btn-atender" href="atender.php?id_cita=<?= $row['id_cita'] ?>">Atender</a>
                            <?php elseif ($row['estado_atencion'] === 'atendiendo'): ?>
                                <span class="btn-en-proceso">En proceso</span>
                            <?php else: ?>
                                <span class="btn-ya-atendida">Ya atendida</span>
                            <?php endif; ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a class="btn-volver" href="home_vet.php">Volver al inicio</a>
</main>

</body>
</html>
