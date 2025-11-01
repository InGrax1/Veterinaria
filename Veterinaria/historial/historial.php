<?php
// 1. Definir variables para la plantilla
$page_title = "Historial Clínico - Patitas Felices";
$page_css = "/Veterinaria/Veterinaria/css/histo.css"; // Ruta absoluta al CSS

// 2. Incluir el header (subiendo un nivel con ../)
include '../includes/plantilla_header.php';

// 3. Lógica específica de esta página
include '../includes/scriptdb.php';

$id_propietario = $_SESSION['id_propietario'];

// --- NUEVA LÓGICA ---
// 1. Obtenemos todas las mascotas del propietario para las pestañas
$sql_mascotas = "SELECT * FROM mascota WHERE id_propietario = ?";
$stmt_mascotas = $conn->prepare($sql_mascotas);
$stmt_mascotas->bind_param("i", $id_propietario);
$stmt_mascotas->execute();
$result_mascotas = $stmt_mascotas->get_result();
$mascotas = [];
while ($row = $result_mascotas->fetch_assoc()) {
    $mascotas[] = $row;
}
$stmt_mascotas->close();

// 2. Determinamos la mascota seleccionada
$selected_pet_id = null;
$selected_pet_info = null;

if (!empty($mascotas)) {
    if (isset($_GET['pet_id'])) {
        $selected_pet_id = $_GET['pet_id'];
        // Validar que esta mascota pertenece al propietario
        foreach ($mascotas as $mascota) {
            if ($mascota['id_mascota'] == $selected_pet_id) {
                $selected_pet_info = $mascota;
                break;
            }
        }
    } else {
        // Si no hay pet_id, seleccionamos la primera mascota por defecto
        $selected_pet_id = $mascotas[0]['id_mascota'];
        $selected_pet_info = $mascotas[0];
    }
}

// 3. Obtenemos el historial SOLO de la mascota seleccionada
$historial = [];
if ($selected_pet_id): // <-- INICIO DEL BLOQUE IF

    // Tu consulta UNION ALL, pero filtrando por id_mascota
    $query = "
        SELECT h.id_historial, h.diagnostico, h.tratamiento, h.notas, h.recetas, NULL AS medicamentos, NULL AS receta_notas, NULL AS fecha, 'historial' AS tipo
        FROM historial_clinico h JOIN mascota m ON m.id_mascota = h.id_mascota
        WHERE m.id_propietario = ? AND m.id_mascota = ?
    UNION ALL
        SELECT r.id_receta, r.diagnostico, r.tratamiento, r.notas, NULL, r.medicamentos, r.notas, r.fecha, 'receta' AS tipo
        FROM receta r JOIN cita_medica c ON r.id_cita = c.id_cita JOIN mascota m ON c.id_mascota = m.id_mascota
        WHERE m.id_propietario = ? AND m.id_mascota = ?
    UNION ALL
        SELECT v.id_cartilla, v.tipo_vacuna, NULL, NULL, NULL, NULL, NULL, v.fecha_aplicacion, 'vacuna' AS tipo
        FROM cartilla_vacunacion v JOIN mascota m ON v.id_mascota = m.id_mascota
        WHERE m.id_propietario = ? AND m.id_mascota = ?
    ORDER BY fecha DESC, id_historial DESC
    ";

    $stmt = $conn->prepare($query);
    // Necesitamos 6 parámetros: (id_propietario, id_mascota) x 3
    $stmt->bind_param("iiiiii", $id_propietario, $selected_pet_id, $id_propietario, $selected_pet_id, $id_propietario, $selected_pet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    $stmt->close();
    
// ¡¡AQUÍ ESTABA EL ERROR!! quite el '}' que estaba en esta línea
endif; // <-- FIN DEL BLOQUE IF (movido aquí para englobar la lógica)

$conn->close();
?>

<main class="page-content">
    <div class="container">
        
        <div class="page-header">
            <div>
                <h1>Historial Clínico</h1>
                <p>Revisa el historial de visitas y tratamientos de tus mascotas.</p>
            </div>
            <a href="#" class="btn-download">
                <i class="fas fa-download"></i> Descargar Historial
            </a>
        </div>

        <?php if (!empty($mascotas)): ?>
            <nav class="pet-tabs">
                <?php foreach ($mascotas as $mascota): ?>
                    <a href="historial.php?pet_id=<?= $mascota['id_mascota'] ?>"
                       class="pet-tab <?php if ($mascota['id_mascota'] == $selected_pet_id) echo 'active'; ?>">
                       <?= htmlspecialchars($mascota['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if ($selected_pet_info): ?>
                <section class="pet-info-card">
                    <img src="https://i.postimg.cc/PfTfF3fF/<?php echo ($selected_pet_info['especie'] == 'Gato') ? 'cat' : 'dog'; ?>-placeholder.jpg" alt="Foto de <?= htmlspecialchars($selected_pet_info['nombre']) ?>">
                    <div class="pet-info-details">
                        <h3><?= htmlspecialchars($selected_pet_info['nombre']) ?></h3>
                        <p>
                            <?= htmlspecialchars($selected_pet_info['especie']) ?> - <?= htmlspecialchars($selected_pet_info['raza']) ?>, 
                            <?= htmlspecialchars($selected_pet_info['edad']) ?> años
                        </p>
                    </div>
                </section>

                <section class="timeline-section">
                    <h2 class="timeline-title">Cronología de Eventos Clínicos</h2>
                    <div class="timeline">
                        <?php if (!empty($historial)): ?>
                            <?php foreach ($historial as $evento): ?>
                                <?php
                                    // Definir icono y título basado en el tipo
                                    $icono = 'fa-file-medical';
                                    $titulo = 'Historial Clínico';
                                    $fecha = $evento['fecha'] ? date("d/m/Y", strtotime($evento['fecha'])) : 'Fecha no registrada';

                                    if ($evento['tipo'] === 'receta') {
                                        $icono = 'fa-prescription-bottle-medical';
                                        $titulo = 'Receta Médica';
                                    } elseif ($evento['tipo'] === 'vacuna') {
                                        $icono = 'fa-syringe';
                                        $titulo = htmlspecialchars($evento['diagnostico']); // Ej. "Vacuna Múltiple"
                                    }
                                ?>
                                <details class="timeline-item">
                                    <summary class="timeline-header">
                                        <div class="timeline-icon-container">
                                            <i class="fas <?= $icono ?>"></i>
                                        </div>
                                        <div class="timeline-summary-info">
                                            <strong><?= $titulo ?></strong>
                                            <small><?= $fecha ?></small>
                                        </div>
                                        <i class="fas fa-chevron-down chevron-icon"></i>
                                    </summary>
                                    <div class="timeline-content">
                                        <?php if ($evento['tipo'] === 'historial'): ?>
                                            <p><strong>Diagnóstico:</strong> <?= htmlspecialchars($evento['diagnostico']) ?></p>
                                            <p><strong>Tratamiento:</strong> <?= htmlspecialchars($evento['tratamiento']) ?></p>
                                            <p><strong>Notas:</strong> <?= htmlspecialchars($evento['notas']) ?></p>
                                        <?php elseif ($evento['tipo'] === 'receta'): ?>
                                            <p><strong>Diagnóstico:</strong> <?= htmlspecialchars($evento['diagnostico']) ?></p>
                                            <p><strong>Medicamentos:</strong> <?= htmlspecialchars($evento['medicamentos']) ?></p>
                                            <p><strong>Notas:</strong> <?= htmlspecialchars($evento['notas']) ?></p>
                                        <?php else: // 'vacuna' ?>
                                            <p><strong>Fecha de Aplicación:</strong> <?= $fecha ?></p>
                                        <?php endif; ?>
                                    </div>
                                </details>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-citas-container minimal">
                                <img src="data:image/jpeg;base64,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" alt="Gato durmiendo" class="no-citas-img-small">
                                <h3>No hay eventos</h3>
                                <p>Esta mascota aún no tiene registros en su historial.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            
            <?php endif; ?>

        <?php else: ?>
            <div class="no-citas-container">
                <img src="data:image/jpeg;base64,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" alt="Gato durmiendo" class="no-citas-img">
                <h3>Aún no hay mascotas registradas</h3>
                <p>No podemos mostrar un historial si no tienes mascotas. ¡Registra una primero!</p>
                <a href="/Veterinaria/Veterinaria/propietario/perfil.php" class="btn-primary">Ir a mi Perfil para registrar</a>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php
// 5. Incluir el footer (subiendo un nivel con ../)
include '../includes/plantilla_footer.php';
?>