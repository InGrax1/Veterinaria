<?php
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

<style>
    .section-header {
        margin-bottom: 25px;
    }

    .section-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 15px;
    }

    .vista-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        background: white;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .vista-tab {
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        color: #4a5568;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        background: transparent;
    }

    .vista-tab:hover {
        background: #fef3e2;
        color: #f09e2b;
    }

    .vista-tab.active {
        background: #f09e2b;
        color: white;
    }

    .table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-top: 20px;
    }

    .patients-table {
        width: 100%;
        border-collapse: collapse;
    }

    .patients-table thead {
        background: #f7fafc;
    }

    .patients-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .patients-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f3f5;
    }

    .patients-table tr:hover {
        background: #fef3e2;
    }

    .btn-atender-table {
        background: #f09e2b;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-block;
    }

    .btn-atender-table:hover {
        background: #d88a1f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(240, 158, 43, 0.3);
    }

    .estado-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .estado-esperando {
        background: #fff3cd;
        color: #856404;
    }

    .estado-atendiendo {
        background: #d1ecf1;
        color: #0c5460;
    }

    .estado-atendida {
        background: #d4edda;
        color: #155724;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 15px;
    }
</style>

<div class="section-header">
    <h2><?= $titulo ?></h2>
    
    <div class="vista-tabs">
        <a href="?seccion=pacientes&vista=hoy" class="vista-tab <?= $vista === 'hoy' ? 'active' : '' ?>">
            📅 Hoy
        </a>
        <a href="?seccion=pacientes&vista=proximos" class="vista-tab <?= $vista === 'proximos' ? 'active' : '' ?>">
            ⏭️ Próximos 7 días
        </a>
        <a href="?seccion=pacientes&vista=anteriores" class="vista-tab <?= $vista === 'anteriores' ? 'active' : '' ?>">
            ⏮️ Últimos 7 días
        </a>
        <a href="?seccion=pacientes&vista=mes_actual" class="vista-tab <?= $vista === 'mes_actual' ? 'active' : '' ?>">
            📊 Mes Actual
        </a>
        <a href="?seccion=pacientes&vista=mes_pasado" class="vista-tab <?= $vista === 'mes_pasado' ? 'active' : '' ?>">
            📈 Mes Pasado
        </a>
    </div>
</div>

<div class="table-wrapper">
    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <table class="patients-table">
            <thead>
                <tr>
                    <th>HORA</th>
                    <th>MASCOTA</th>
                    <th>ESPECIE</th>
                    <th>RAZA</th>
                    <th>PROPIETARIO</th>
                    <?php if ($vista === 'hoy'): ?>
                        <th>MOTIVO</th>
                        <th>ESTADO</th>
                        <th>ACCIÓN</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><strong><?= date('H:i', strtotime($row['fecha_hora'])) ?></strong></td>
                        <td><strong><?= htmlspecialchars($row['nombre_mascota']) ?></strong></td>
                        <td><?= htmlspecialchars($row['especie']) ?></td>
                        <td><?= htmlspecialchars($row['raza']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_propietario']) ?></td>
                        <?php if ($vista === 'hoy'): ?>
                            <td><?= htmlspecialchars($row['motivo']) ?></td>
                            <td>
                                <?php
                                $estado = $row['estado_atencion'];
                                $clase = '';
                                $texto = '';
                                
                                switch($estado) {
                                    case 'esperando':
                                        $clase = 'estado-esperando';
                                        $texto = 'Esperando';
                                        break;
                                    case 'atendiendo':
                                        $clase = 'estado-atendiendo';
                                        $texto = 'En proceso';
                                        break;
                                    case 'atendida':
                                        $clase = 'estado-atendida';
                                        $texto = 'Atendida';
                                        break;
                                }
                                ?>
                                <span class="estado-badge <?= $clase ?>"><?= $texto ?></span>
                            </td>
                            <td>
                                <?php if ($row['estado_atencion'] === 'esperando' || $row['estado_atencion'] === 'atendiendo'): ?>
                                    <a href="atender_integrado.php?id_cita=<?= $row['id_cita'] ?>" class="btn-atender-table">
                                        Atender
                                    </a>
                                <?php else: ?>
                                    <span style="color: #a0aec0;">Completada</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>No hay citas programadas</h3>
            <p>No se encontraron pacientes para este período</p>
        </div>
    <?php endif; ?>
</div>