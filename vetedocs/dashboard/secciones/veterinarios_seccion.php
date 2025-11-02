<?php
// Procesar actualización de veterinario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_veterinario'])) {
    $id_veterinario = intval($_POST['id_veterinario']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $especialidad = mysqli_real_escape_string($conn, $_POST['especialidad']);

    $update_query = "UPDATE veterinario 
                     SET nombre='$nombre', telefono='$telefono', 
                         correo_electronico='$correo', especialidad='$especialidad' 
                     WHERE id_veterinario = $id_veterinario";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>
            window.location.href = '?seccion=veterinarios&actualizado=1';
        </script>";
    } else {
        $error_mensaje = "Error al actualizar: " . mysqli_error($conn);
    }
}

// Procesar eliminación de veterinario
if (isset($_GET['eliminar']) && in_array($_SESSION['veterinario_id'], [1, 2, 3])) {
    $id_eliminar = intval($_GET['eliminar']);
    $delete_query = "DELETE FROM veterinario WHERE id_veterinario = $id_eliminar";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
            window.location.href = '?seccion=veterinarios&eliminado=1';
        </script>";
    }
}

$resultado = mysqli_query($conn, "SELECT * FROM veterinario ORDER BY nombre");
?>

<style>
    .vet-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .vet-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a202c;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
        font-weight: 500;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        font-weight: 500;
    }

    .vet-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .vet-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .vet-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-color: #f09e2b;
    }

    .vet-card.editing {
        border-color: #f09e2b;
        box-shadow: 0 8px 20px rgba(240, 158, 43, 0.2);
    }

    .vet-card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f3f5;
    }

    .vet-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f09e2b, #fbbf24);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .vet-info h3 {
        color: #1a202c;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .vet-specialty {
        color: #f09e2b;
        font-size: 14px;
        font-weight: 600;
    }

    .vet-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .vet-detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #4a5568;
        font-size: 14px;
    }

    .vet-detail-icon {
        font-size: 18px;
        width: 25px;
    }

    /* Formulario de edición inline */
    .edit-form {
        display: none;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f1f3f5;
    }

    .edit-form.active {
        display: block;
    }

    .form-group-inline {
        margin-bottom: 15px;
    }

    .form-label-inline {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input-inline {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-input-inline:focus {
        outline: none;
        border-color: #f09e2b;
        box-shadow: 0 0 0 3px rgba(240, 158, 43, 0.1);
    }

    .vet-actions {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f1f3f5;
        display: flex;
        gap: 10px;
    }

    .btn-vet {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #3b82f6;
        color: white;
    }

    .btn-edit:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-save {
        background: #10b981;
        color: white;
    }

    .btn-save:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-cancel {
        background: #6b7280;
        color: white;
    }

    .btn-cancel:hover {
        background: #4b5563;
    }

    .view-mode {
        display: block;
    }

    .view-mode.hidden {
        display: none;
    }

    .empty-state-vets {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        color: #a0aec0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .empty-state-vets-icon {
        font-size: 64px;
        margin-bottom: 15px;
    }
</style>

<div class="vet-header">
    <h2>👨‍⚕️ Veterinarios Registrados</h2>
</div>

<?php if (isset($_GET['actualizado'])): ?>
    <div class="alert-success">✅ Veterinario actualizado correctamente</div>
<?php endif; ?>

<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert-success">✅ Veterinario eliminado correctamente</div>
<?php endif; ?>

<?php if (isset($error_mensaje)): ?>
    <div class="alert-error">❌ <?= $error_mensaje ?></div>
<?php endif; ?>

<?php if (mysqli_num_rows($resultado) > 0): ?>
    <div class="vet-cards-grid">
        <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
            <div class="vet-card" id="card-<?= $row['id_veterinario'] ?>">
                <!-- Vista normal -->
                <div class="view-mode" id="view-<?= $row['id_veterinario'] ?>">
                    <div class="vet-card-header">
                        <div class="vet-avatar">
                            <?= strtoupper(substr($row['nombre'], 0, 2)) ?>
                        </div>
                        <div class="vet-info">
                            <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                            <span class="vet-specialty"><?= htmlspecialchars($row['especialidad']) ?></span>
                        </div>
                    </div>

                    <div class="vet-details">
                        <div class="vet-detail-item">
                            <span class="vet-detail-icon">📧</span>
                            <span><?= htmlspecialchars($row['correo_electronico']) ?></span>
                        </div>
                        <div class="vet-detail-item">
                            <span class="vet-detail-icon">📱</span>
                            <span><?= htmlspecialchars($row['telefono']) ?></span>
                        </div>
                    </div>

                    <?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])): ?>
                        <div class="vet-actions">
                            <button onclick="editarVeterinario(<?= $row['id_veterinario'] ?>)" 
                                    class="btn-vet btn-edit">
                                ✏️ Editar
                            </button>
                            <button onclick="confirmarEliminar(<?= $row['id_veterinario'] ?>, '<?= htmlspecialchars($row['nombre']) ?>')" 
                                    class="btn-vet btn-delete">
                                🗑️ Eliminar
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Formulario de edición -->
                <?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])): ?>
                    <form method="POST" class="edit-form" id="edit-<?= $row['id_veterinario'] ?>">
                        <input type="hidden" name="id_veterinario" value="<?= $row['id_veterinario'] ?>">
                        
                        <div class="form-group-inline">
                            <label class="form-label-inline">Nombre completo</label>
                            <input type="text" name="nombre" class="form-input-inline" 
                                   value="<?= htmlspecialchars($row['nombre']) ?>" required>
                        </div>

                        <div class="form-group-inline">
                            <label class="form-label-inline">Especialidad</label>
                            <input type="text" name="especialidad" class="form-input-inline" 
                                   value="<?= htmlspecialchars($row['especialidad']) ?>" required>
                        </div>

                        <div class="form-group-inline">
                            <label class="form-label-inline">Correo electrónico</label>
                            <input type="email" name="correo" class="form-input-inline" 
                                   value="<?= htmlspecialchars($row['correo_electronico']) ?>" required>
                        </div>

                        <div class="form-group-inline">
                            <label class="form-label-inline">Teléfono</label>
                            <input type="tel" name="telefono" class="form-input-inline" 
                                   value="<?= htmlspecialchars($row['telefono']) ?>" required>
                        </div>

                        <div class="vet-actions">
                            <button type="submit" name="actualizar_veterinario" class="btn-vet btn-save">
                                💾 Guardar
                            </button>
                            <button type="button" onclick="cancelarEdicion(<?= $row['id_veterinario'] ?>)" 
                                    class="btn-vet btn-cancel">
                                ✖️ Cancelar
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state-vets">
        <div class="empty-state-vets-icon">👨‍⚕️</div>
        <h3>No hay veterinarios registrados</h3>
        <p>Aún no se han registrado veterinarios en el sistema</p>
    </div>
<?php endif; ?>

<script>
function editarVeterinario(id) {
    // Ocultar vista normal
    document.getElementById('view-' + id).classList.add('hidden');
    // Mostrar formulario
    document.getElementById('edit-' + id).classList.add('active');
    // Agregar clase editing a la card
    document.getElementById('card-' + id).classList.add('editing');
}

function cancelarEdicion(id) {
    // Mostrar vista normal
    document.getElementById('view-' + id).classList.remove('hidden');
    // Ocultar formulario
    document.getElementById('edit-' + id).classList.remove('active');
    // Quitar clase editing de la card
    document.getElementById('card-' + id).classList.remove('editing');
}

function confirmarEliminar(id, nombre) {
    if (confirm('¿Estás seguro de eliminar a ' + nombre + '?\n\nEsta acción no se puede deshacer.')) {
        window.location.href = '?seccion=veterinarios&eliminar=' + id;
    }
}
</script>