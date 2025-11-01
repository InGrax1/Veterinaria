<?php
// Obtener estadísticas
$hoy = date('Y-m-d');
$fecha_7dias = date('Y-m-d', strtotime('+7 days'));
$inicio_mes = date('Y-m-01');
$mes_pasado_inicio = date('Y-m-01', strtotime('-1 month'));
$mes_pasado_fin = date('Y-m-t', strtotime('-1 month'));

// Pacientes de hoy
$query_hoy = "SELECT COUNT(*) as total FROM cita_medica WHERE DATE(fecha_hora) = CURDATE()";
$result_hoy = mysqli_query($conn, $query_hoy);
$pacientes_hoy = mysqli_fetch_assoc($result_hoy)['total'];

// Próximos 7 días
$query_proximos = "SELECT COUNT(*) as total FROM cita_medica 
                   WHERE DATE(fecha_hora) > CURDATE() AND DATE(fecha_hora) <= '$fecha_7dias'";
$result_proximos = mysqli_query($conn, $query_proximos);
$proximos_7 = mysqli_fetch_assoc($result_proximos)['total'];

// Últimos 7 días
$fecha_7dias_atras = date('Y-m-d', strtotime('-7 days'));
$query_ultimos = "SELECT COUNT(*) as total FROM cita_medica 
                  WHERE DATE(fecha_hora) >= '$fecha_7dias_atras' AND DATE(fecha_hora) < CURDATE()";
$result_ultimos = mysqli_query($conn, $query_ultimos);
$ultimos_7 = mysqli_fetch_assoc($result_ultimos)['total'];

// Mes actual
$query_mes = "SELECT COUNT(*) as total FROM cita_medica 
              WHERE DATE(fecha_hora) >= '$inicio_mes' AND DATE(fecha_hora) <= CURDATE()";
$result_mes = mysqli_query($conn, $query_mes);
$mes_actual = mysqli_fetch_assoc($result_mes)['total'];

// Mes pasado
$query_mes_pasado = "SELECT COUNT(*) as total FROM cita_medica 
                     WHERE DATE(fecha_hora) BETWEEN '$mes_pasado_inicio' AND '$mes_pasado_fin'";
$result_mes_pasado = mysqli_query($conn, $query_mes_pasado);
$mes_pasado = mysqli_fetch_assoc($result_mes_pasado)['total'];

// Citas recientes de hoy
$query_citas = "SELECT c.id_cita, c.fecha_hora, c.estado_atencion, c.motivo,
                       m.nombre AS mascota, m.especie,
                       p.nombre AS propietario,
                       v.nombre AS veterinario
                FROM cita_medica c
                JOIN mascota m ON c.id_mascota = m.id_mascota
                JOIN propietario p ON m.id_propietario = p.id_propietario
                LEFT JOIN veterinario v ON c.id_veterinario = v.id_veterinario
                WHERE DATE(c.fecha_hora) = CURDATE()
                ORDER BY c.fecha_hora
                LIMIT 10";
$result_citas = mysqli_query($conn, $query_citas);
?>

<style>
    .welcome-section {
        margin-bottom: 30px;
    }

    .welcome-title {
        font-size: 32px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 8px;
    }

    .welcome-subtitle {
        font-size: 16px;
        color: #718096;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #f09e2b;
        color: white;
    }

    .btn-primary:hover {
        background: #d88a1f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(240, 158, 43, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        border-color: #f09e2b;
        color: #f09e2b;
    }

    /* Cards de estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-color: #f09e2b;
    }

    .stat-label {
        font-size: 14px;
        color: #718096;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: #1a202c;
    }

    .stat-card.primary {
        background: linear-gradient(135deg, #f09e2b, #fbbf24);
        color: white;
    }

    .stat-card.primary .stat-label,
    .stat-card.primary .stat-value {
        color: white;
    }

    /* Tabla de citas recientes */
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 20px;
    }

    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .citas-table {
        width: 100%;
        border-collapse: collapse;
    }

    .citas-table thead {
        background: #f7fafc;
    }

    .citas-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .citas-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f3f5;
        color: #2d3748;
    }

    .citas-table tr:hover {
        background: #fef3e2;
    }

    .patient-info {
        display: flex;
        flex-direction: column;
    }

    .patient-name {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 2px;
    }

    .patient-species {
        font-size: 13px;
        color: #718096;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
    }

    .status-confirmado {
        background: #d4edda;
        color: #155724;
    }

    .status-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .status-cancelado {
        background: #f8d7da;
        color: #721c24;
    }

    .status-esperando {
        background: #cce5ff;
        color: #004085;
    }

    .btn-ver-detalles {
        color: #f09e2b;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-ver-detalles:hover {
        color: #d88a1f;
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
</style>

<div class="welcome-section">
    <h1 class="welcome-title">Buenos días, Dr. <?php echo htmlspecialchars($_SESSION['veterinario_nombre']); ?></h1>
    <p class="welcome-subtitle">Bienvenido al panel de administración.</p>
    
    <div class="action-buttons">
        <a href="?seccion=registrar_vet" class="btn-action btn-primary">
            ➕ Registrar Veterinario
        </a>
        <a href="?seccion=pacientes" class="btn-action btn-secondary">
            📅 Programar Cita
        </a>
    </div>
</div>

<!-- Estadísticas -->
<h2 class="section-title">Resumen de Pacientes</h2>
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-label">Pacientes de hoy</div>
        <div class="stat-value"><?= $pacientes_hoy ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Próximos 7 días</div>
        <div class="stat-value"><?= $proximos_7 ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Últimos 7 días</div>
        <div class="stat-value"><?= $ultimos_7 ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Mes actual</div>
        <div class="stat-value"><?= $mes_actual ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Mes pasado</div>
        <div class="stat-value"><?= $mes_pasado ?></div>
    </div>
</div>

<!-- Citas recientes -->
<h2 class="section-title">Citas Recientes</h2>
<div class="table-container">
    <?php if (mysqli_num_rows($result_citas) > 0): ?>
        <table class="citas-table">
            <thead>
                <tr>
                    <th>HORA</th>
                    <th>PACIENTE</th>
                    <th>DUEÑO</th>
                    <th>VETERINARIO</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cita = mysqli_fetch_assoc($result_citas)): ?>
                    <tr>
                        <td><strong><?= date('h:i A', strtotime($cita['fecha_hora'])) ?></strong></td>
                        <td>
                            <div class="patient-info">
                                <span class="patient-name"><?= htmlspecialchars($cita['mascota']) ?> (<?= htmlspecialchars($cita['especie']) ?>)</span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($cita['propietario']) ?></td>
                        <td><?= htmlspecialchars($cita['veterinario'] ?: 'No asignado') ?></td>
                        <td>
                            <?php
                            $estado = $cita['estado_atencion'];
                            $clase = '';
                            $texto = '';
                            
                            switch($estado) {
                                case 'esperando':
                                    $clase = 'status-esperando';
                                    $texto = 'Pendiente';
                                    break;
                                case 'atendiendo':
                                    $clase = 'status-pendiente';
                                    $texto = 'En proceso';
                                    break;
                                case 'atendida':
                                    $clase = 'status-confirmado';
                                    $texto = 'Confirmado';
                                    break;
                                default:
                                    $clase = 'status-confirmado';
                                    $texto = 'Confirmado';
                            }
                            ?>
                            <span class="status-badge <?= $clase ?>"><?= $texto ?></span>
                        </td>
                        <td>
                            <a href="atender_integrado.php?id_cita=<?= $cita['id_cita'] ?>" class="btn-ver-detalles">
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3>No hay citas programadas para hoy</h3>
            <p>Las próximas citas aparecerán aquí</p>
        </div>
    <?php endif; ?>
</div>