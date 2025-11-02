<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

if (!isset($_GET['id_cita'])) {
    die("ID de cita no proporcionado.");
}

$id_cita = intval($_GET['id_cita']);

// Obtener datos de la cita
$query = "SELECT c.fecha_hora, c.motivo, c.estado_atencion,
                 m.nombre AS mascota, m.especie, m.raza, m.edad, m.sexo, m.id_mascota,
                 p.nombre AS propietario, p.telefono AS tel_propietario,
                 v.nombre AS veterinario, v.especialidad
          FROM cita_medica c
          JOIN mascota m ON c.id_mascota = m.id_mascota
          JOIN propietario p ON m.id_propietario = p.id_propietario
          LEFT JOIN veterinario v ON c.id_veterinario = v.id_veterinario
          WHERE c.id_cita = $id_cita";
$result = mysqli_query($conn, $query);
$cita = mysqli_fetch_assoc($result);

if (!$cita) {
    die("Cita no encontrada.");
}

$id_mascota = $cita['id_mascota'];

// Obtener historial clínico
$historial_query = "SELECT * FROM historial_clinico WHERE id_mascota = $id_mascota ORDER BY id_historial DESC LIMIT 1";
$historial_result = mysqli_query($conn, $historial_query);
$historial = $historial_result ? mysqli_fetch_assoc($historial_result) : null;

// Obtener recetas
$recetas_query = "SELECT * FROM receta WHERE id_cita = $id_cita ORDER BY fecha DESC";
$recetas_result = mysqli_query($conn, $recetas_query);

// Obtener vacunas
$vacunas_query = "SELECT * FROM cartilla_vacunacion WHERE id_mascota = $id_mascota ORDER BY fecha_aplicacion DESC LIMIT 5";
$vacunas_result = mysqli_query($conn, $vacunas_query);

// Generar HTML del PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #f09e2b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #f09e2b;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #f09e2b;
        }
        .section-title {
            color: #f09e2b;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #f09e2b;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }
        .info-item {
            padding: 8px;
            background: white;
            border-radius: 5px;
        }
        .info-item strong {
            color: #555;
            display: block;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .info-item span {
            color: #000;
            font-size: 14px;
        }
        .content-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .content-box p {
            margin: 8px 0;
            line-height: 1.6;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background: #f09e2b;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        @media print {
            body {
                margin: 20px;
            }
            .header {
                page-break-after: avoid;
            }
            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🐾 Clínica Veterinaria Patitas Felices</h1>
        <p>Reporte de Atención Médica</p>
        <p>Fecha de generación: ' . date('d/m/Y H:i') . '</p>
    </div>

    <!-- Información de la Cita -->
    <div class="section">
        <div class="section-title">📋 Información de la Cita</div>
        <div class="info-grid">
            <div class="info-item">
                <strong>Fecha y Hora</strong>
                <span>' . date('d/m/Y H:i', strtotime($cita['fecha_hora'])) . '</span>
            </div>
            <div class="info-item">
                <strong>Estado</strong>
                <span class="badge badge-success">✅ Atendida</span>
            </div>
            <div class="info-item">
                <strong>Motivo de Consulta</strong>
                <span>' . htmlspecialchars($cita['motivo']) . '</span>
            </div>
            <div class="info-item">
                <strong>Veterinario</strong>
                <span>' . htmlspecialchars($cita['veterinario'] ?: 'No asignado') . '</span>
            </div>
        </div>
    </div>

    <!-- Información del Paciente -->
    <div class="section">
        <div class="section-title">🐕 Información del Paciente</div>
        <div class="info-grid">
            <div class="info-item">
                <strong>Nombre</strong>
                <span>' . htmlspecialchars($cita['mascota']) . '</span>
            </div>
            <div class="info-item">
                <strong>Especie</strong>
                <span>' . htmlspecialchars($cita['especie']) . '</span>
            </div>
            <div class="info-item">
                <strong>Raza</strong>
                <span>' . htmlspecialchars($cita['raza']) . '</span>
            </div>
            <div class="info-item">
                <strong>Edad</strong>
                <span>' . htmlspecialchars($cita['edad']) . ' años</span>
            </div>
            <div class="info-item">
                <strong>Sexo</strong>
                <span>' . htmlspecialchars($cita['sexo']) . '</span>
            </div>
        </div>
    </div>

    <!-- Información del Propietario -->
    <div class="section">
        <div class="section-title">👤 Información del Propietario</div>
        <div class="info-grid">
            <div class="info-item">
                <strong>Nombre</strong>
                <span>' . htmlspecialchars($cita['propietario']) . '</span>
            </div>
            <div class="info-item">
                <strong>Teléfono</strong>
                <span>' . htmlspecialchars($cita['tel_propietario']) . '</span>
            </div>
        </div>
    </div>';

// Historial Clínico
if ($historial) {
    $html .= '
    <div class="section">
        <div class="section-title">📝 Historial Clínico</div>
        <div class="content-box">
            <p><strong>Diagnóstico:</strong><br>' . nl2br(htmlspecialchars($historial['diagnostico'])) . '</p>
            <p><strong>Tratamiento:</strong><br>' . nl2br(htmlspecialchars($historial['tratamiento'])) . '</p>';
    
    if ($historial['notas']) {
        $html .= '<p><strong>Notas:</strong><br>' . nl2br(htmlspecialchars($historial['notas'])) . '</p>';
    }
    
    if ($historial['recetas']) {
        $html .= '<p><strong>Recetas:</strong><br>' . nl2br(htmlspecialchars($historial['recetas'])) . '</p>';
    }
    
    $html .= '</div></div>';
}

// Recetas Médicas
if (mysqli_num_rows($recetas_result) > 0) {
    $html .= '
    <div class="section">
        <div class="section-title">💊 Recetas Médicas</div>';
    
    while ($receta = mysqli_fetch_assoc($recetas_result)) {
        $html .= '
        <div class="content-box" style="margin-bottom: 10px;">
            <p><small>Fecha: ' . date('d/m/Y H:i', strtotime($receta['fecha'])) . '</small></p>
            <p><strong>Diagnóstico:</strong> ' . htmlspecialchars($receta['diagnostico']) . '</p>
            <p><strong>Tratamiento:</strong> ' . htmlspecialchars($receta['tratamiento']) . '</p>
            <p><strong>Medicamentos:</strong><br>' . nl2br(htmlspecialchars($receta['medicamentos'])) . '</p>
            <p><strong>Notas:</strong><br>' . nl2br(htmlspecialchars($receta['notas'])) . '</p>
        </div>';
    }
    
    $html .= '</div>';
}

// Vacunas
if (mysqli_num_rows($vacunas_result) > 0) {
    $html .= '
    <div class="section">
        <div class="section-title">💉 Cartilla de Vacunación (Últimas 5)</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Vacuna</th>
                    <th>Fecha de Aplicación</th>
                </tr>
            </thead>
            <tbody>';
    
    while ($vacuna = mysqli_fetch_assoc($vacunas_result)) {
        $html .= '
                <tr>
                    <td>' . htmlspecialchars($vacuna['tipo_vacuna']) . '</td>
                    <td>' . date('d/m/Y', strtotime($vacuna['fecha_aplicacion'])) . '</td>
                </tr>';
    }
    
    $html .= '
            </tbody>
        </table>
    </div>';
}

$html .= '
    <div class="footer">
        <p>Este documento es un reporte médico veterinario oficial</p>
        <p>Clínica Veterinaria Patitas Felices | Tel: (555) 123-4567</p>
        <p>Generado el ' . date('d/m/Y') . ' a las ' . date('H:i') . '</p>
    </div>
</body>
</html>';

// Generar el documento HTML
header('Content-Type: text/html; charset=UTF-8');
echo $html;

// NOTA: Para generar PDF real, necesitas instalar DomPDF
// Comando: composer require dompdf/dompdf
// 
// Luego usar:
// require_once '../vendor/autoload.php';
// use Dompdf\Dompdf;
// $dompdf = new Dompdf();
// $dompdf->loadHtml($html);
// $dompdf->setPaper('A4', 'portrait');
// $dompdf->render();
// $dompdf->stream("Cita_" . $id_cita . ".pdf");
?>