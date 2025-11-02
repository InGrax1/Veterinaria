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

// Obtener datos de la cita con estado
$query = "SELECT c.fecha_hora, c.motivo, c.estado_atencion, m.nombre AS mascota, m.id_mascota, p.nombre AS propietario
          FROM cita_medica c
          JOIN mascota m ON c.id_mascota = m.id_mascota
          JOIN propietario p ON m.id_propietario = p.id_propietario
          WHERE c.id_cita = $id_cita";
$result = mysqli_query($conn, $query);
$cita = mysqli_fetch_assoc($result);

if (!$cita) {
    echo "Cita no encontrada.";
    exit;
}

$id_mascota = $cita['id_mascota'];
$estado_cita = $cita['estado_atencion'];
$solo_lectura = ($estado_cita === 'atendida'); // Si está atendida, es solo lectura

// Marcar como atendiendo solo si no está completada
if (!$solo_lectura && $estado_cita === 'esperando') {
    $update = "UPDATE cita_medica SET estado_atencion = 'atendiendo' WHERE id_cita = $id_cita";
    mysqli_query($conn, $update);
}

$mensaje = "";

// Solo permitir guardar si NO es solo lectura
if (!$solo_lectura) {
    // Procesar formulario de historial clínico
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_historial'])) {
        $diagnostico = $_POST['diagnostico'];
        $tratamiento = $_POST['tratamiento'];
        $notas = $_POST['notas'];
        $recetas = $_POST['recetas'];
        $tipo_vacuna = $_POST['tipo_vacuna'];
        $fecha_aplicacion = $_POST['fecha_aplicacion'];

        $sql1 = "INSERT INTO historial_clinico (id_mascota, diagnostico, tratamiento, notas, recetas)
                 VALUES (?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("issss", $id_mascota, $diagnostico, $tratamiento, $notas, $recetas);
        $stmt1->execute();

        if (!empty($tipo_vacuna) && !empty($fecha_aplicacion)) {
            $sql2 = "INSERT INTO cartilla_vacunacion (id_mascota, tipo_vacuna, fecha_aplicacion)
                     VALUES (?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("iss", $id_mascota, $tipo_vacuna, $fecha_aplicacion);
            $stmt2->execute();
        }

        $mensaje = "✅ Historial clínico guardado con éxito";
    }

    // Procesar formulario de receta
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_receta'])) {
        $diagnostico_rec = $_POST['diagnostico_rec'];
        $tratamiento_rec = $_POST['tratamiento_rec'];
        $medicamentos = $_POST['medicamentos'];
        $contenido = $_POST['contenido'];

        $query = "INSERT INTO receta (id_cita, diagnostico, tratamiento, notas, medicamentos, fecha) 
                  VALUES ($id_cita, '$diagnostico_rec', '$tratamiento_rec', '$contenido', '$medicamentos', NOW())";
        
        if (mysqli_query($conn, $query)) {
            $mensaje = "✅ Receta guardada con éxito";
        }
    }

    // Procesar formulario de siguiente cita
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agendar_cita'])) {
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $motivo = $_POST['motivo'];
        $id_veterinario = $_SESSION['veterinario_id'];
        $fecha_hora = "$fecha $hora";

        $query = "INSERT INTO cita_medica (id_mascota, id_veterinario, fecha_hora, motivo)
                  VALUES ($id_mascota, $id_veterinario, '$fecha_hora', '$motivo')";
        
        if (mysqli_query($conn, $query)) {
            $mensaje = "✅ Cita agendada con éxito";
        }
    }
}

// Obtener historial clínico si existe (ordenar por id ya que no hay columna fecha)
$historial_query = "SELECT * FROM historial_clinico WHERE id_mascota = $id_mascota ORDER BY id_historial DESC LIMIT 1";
$historial_result = mysqli_query($conn, $historial_query);
$historial = $historial_result ? mysqli_fetch_assoc($historial_result) : null;

// Obtener recetas si existen
$recetas_query = "SELECT * FROM receta WHERE id_cita = $id_cita ORDER BY fecha DESC";
$recetas_result = mysqli_query($conn, $recetas_query);

// Obtener vacunas si existen
$vacunas_query = "SELECT * FROM cartilla_vacunacion WHERE id_mascota = $id_mascota ORDER BY fecha_aplicacion DESC";
$vacunas_result = mysqli_query($conn, $vacunas_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $solo_lectura ? 'Ver Detalles' : 'Atender Paciente' ?></title>
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="stylesheet" href="../css/atencion.css">
</head>
<body>
    <div class="container">
        <!-- Información del paciente -->
        <div class="header-card">
            <h1>
                🐾 <?= $solo_lectura ? 'Detalles de' : 'Atendiendo a' ?> <?= htmlspecialchars($cita['mascota']) ?>
                <?php if ($solo_lectura): ?>
                    <span class="badge-completada">✅ Atendida</span>
                <?php else: ?>
                    <span class="badge-atendiendo">🔄 En proceso</span>
                <?php endif; ?>
            </h1>
            
            <div class="patient-info">
                <div class="info-item">
                    <strong>📅 Fecha y Hora</strong>
                    <span><?= date('d/m/Y', strtotime($cita['fecha_hora'])) ?> - <?= date('H:i', strtotime($cita['fecha_hora'])) ?></span>
                </div>
                <div class="info-item">
                    <strong>👤 Propietario</strong>
                    <span><?= htmlspecialchars($cita['propietario']) ?></span>
                </div>
                <div class="info-item">
                    <strong>📋 Motivo de consulta</strong>
                    <span><?= htmlspecialchars($cita['motivo']) ?></span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="home_vet.php?seccion=pacientes" class="btn btn-back">← Volver a pacientes</a>
                <?php if ($solo_lectura): ?>
                    <a href="generar_pdf.php?id_cita=<?= $id_cita ?>" class="btn btn-success" target="_blank">
                        📄 Descargar PDF
                    </a>
                <?php else: ?>
                    <a href="finalizar_atencion.php?id_cita=<?= $id_cita ?>" class="btn btn-danger" 
                       onclick="return confirm('¿Finalizar atención?')">Finalizar Atención</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <div class="success-message"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if ($solo_lectura): ?>
            <div class="readonly-notice">
                ℹ️ Esta cita ya ha sido completada. La información se muestra en modo solo lectura.
            </div>
        <?php endif; ?>

        <!-- Tabs con formularios o vista -->
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab(event, 'historial')">
                    📝 Historial Clínico
                </button>
                <button class="tab-btn" onclick="switchTab(event, 'receta')">
                    💊 Recetas
                </button>
                <button class="tab-btn" onclick="switchTab(event, 'vacunas')">
                    💉 Vacunas
                </button>
            </div>

            <!-- Tab 1: Historial Clínico -->
            <div id="historial" class="tab-content active">
                <h2 class="section-title"><?= $solo_lectura ? 'Historial Clínico' : 'Registrar Historial Clínico' ?></h2>
                
                <?php if ($solo_lectura && $historial): ?>
                    <div class="data-display">
                        <strong>Diagnóstico</strong>
                        <p><?= htmlspecialchars($historial['diagnostico']) ?></p>
                    </div>
                    <div class="data-display">
                        <strong>Tratamiento</strong>
                        <p><?= htmlspecialchars($historial['tratamiento']) ?></p>
                    </div>
                    <?php if ($historial['notas']): ?>
                        <div class="data-display">
                            <strong>Notas adicionales</strong>
                            <p><?= htmlspecialchars($historial['notas']) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($historial['recetas']): ?>
                        <div class="data-display">
                            <strong>Recetas</strong>
                            <p><?= htmlspecialchars($historial['recetas']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php elseif ($solo_lectura): ?>
                    <p style="color: #a0aec0; text-align: center; padding: 40px;">No hay historial clínico registrado</p>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Diagnóstico *</label>
                            <textarea name="diagnostico" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tratamiento *</label>
                            <textarea name="tratamiento" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Notas adicionales</label>
                            <textarea name="notas" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Recetas médicas</label>
                            <textarea name="recetas" class="form-control"></textarea>
                        </div>

                        <h3 class="section-title">Registrar Vacuna (Opcional)</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Tipo de vacuna</label>
                                <input type="text" name="tipo_vacuna" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Fecha de aplicación</label>
                                <input type="date" name="fecha_aplicacion" class="form-control">
                            </div>
                        </div>

                        <button type="submit" name="guardar_historial" class="btn btn-primary">
                            💾 Guardar Historial Clínico
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Tab 2: Recetas -->
            <div id="receta" class="tab-content">
                <h2 class="section-title"><?= $solo_lectura ? 'Recetas Médicas' : 'Crear Receta Médica' ?></h2>
                
                <?php if ($solo_lectura && mysqli_num_rows($recetas_result) > 0): ?>
                    <?php while ($receta = mysqli_fetch_assoc($recetas_result)): ?>
                        <div class="data-display">
                            <strong>Fecha: <?= date('d/m/Y H:i', strtotime($receta['fecha'])) ?></strong>
                            <p><strong>Diagnóstico:</strong> <?= htmlspecialchars($receta['diagnostico']) ?></p>
                            <p><strong>Tratamiento:</strong> <?= htmlspecialchars($receta['tratamiento']) ?></p>
                            <p><strong>Medicamentos:</strong> <?= htmlspecialchars($receta['medicamentos']) ?></p>
                            <p><strong>Notas:</strong> <?= htmlspecialchars($receta['notas']) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php elseif ($solo_lectura): ?>
                    <p style="color: #a0aec0; text-align: center; padding: 40px;">No hay recetas registradas</p>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Diagnóstico *</label>
                            <textarea name="diagnostico_rec" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tratamiento *</label>
                            <textarea name="tratamiento_rec" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Medicamentos *</label>
                            <textarea name="medicamentos" class="form-control" required 
                                      placeholder="Ej: Amoxicilina 500mg - 1 cada 8 horas por 7 días"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Notas y recomendaciones *</label>
                            <textarea name="contenido" class="form-control" required
                                      placeholder="Indicaciones adicionales, cuidados especiales, etc."></textarea>
                        </div>

                        <button type="submit" name="guardar_receta" class="btn btn-primary">
                            💊 Guardar Receta
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Tab 3: Vacunas -->
            <div id="vacunas" class="tab-content">
                <h2 class="section-title">Cartilla de Vacunación</h2>
                
                <?php if (mysqli_num_rows($vacunas_result) > 0): ?>
                    <?php while ($vacuna = mysqli_fetch_assoc($vacunas_result)): ?>
                        <div class="data-display">
                            <strong><?= htmlspecialchars($vacuna['tipo_vacuna']) ?></strong>
                            <p>Aplicada el: <?= date('d/m/Y', strtotime($vacuna['fecha_aplicacion'])) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #a0aec0; text-align: center; padding: 40px;">No hay vacunas registradas</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function switchTab(evt, tabName) {
            var tabContents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }

            var tabBtns = document.getElementsByClassName("tab-btn");
            for (var i = 0; i < tabBtns.length; i++) {
                tabBtns[i].classList.remove("active");
            }

            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>