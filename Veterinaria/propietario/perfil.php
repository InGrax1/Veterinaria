<?php
// 1. Definir variables para la plantilla
$page_title = "Mi Perfil - Patitas Felices";
$page_css = "/Veterinaria/Veterinaria/css/perfil.css"; // Ruta absoluta al CSS

// 2. Incluir el header (subiendo un nivel con ../)
// La sesión ya se inicia en la plantilla.
include '../includes/plantilla_header.php';

// 3. Lógica específica de esta página
include '../includes/scriptdb.php';

// Ya no necesitamos la validación de sesión aquí, se hace en el header.
// if (!isset($_SESSION['id_propietario'])) { ... }

$id_propietario = $_SESSION['id_propietario'];

// Obtener datos del propietario
$sql_propietario = "SELECT * FROM propietario WHERE id_propietario = $id_propietario LIMIT 1";
$result_propietario = mysqli_query($conn, $sql_propietario);
$cliente = mysqli_fetch_assoc($result_propietario);

// Obtener todas las mascotas del propietario
$sql_mascotas = "SELECT * FROM mascota WHERE id_propietario = $id_propietario";
$result_mascotas = mysqli_query($conn, $sql_mascotas);

$mascotas = [];
while ($row = mysqli_fetch_assoc($result_mascotas)) {
    $mascotas[] = $row;
}
?>

<main class="page-content">
    <div class="container">
        <h1 class="page-title">Mi Perfil</h1>

        <div class="profile-layout">
            <div class="profile-card" id="profileCard">
                <h2><?= htmlspecialchars($cliente['nombre']) ?></h2>
                <p class="email"><?= htmlspecialchars($cliente['correo']) ?></p>

                <form action="actualizar_perfil.php" method="POST" id="profileForm">
                    <div class="profile-info">
                        <div class="profile-info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="info-content">
                                <span class="info-label">Dirección</span>
                                <span class="info-value"><?= htmlspecialchars($cliente['direccion']) ?></span>
                                <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" class="form-control">
                            </div>
                        </div>

                        <div class="profile-info-item">
                            <i class="fas fa-phone"></i>
                            <div class="info-content">
                                <span class="info-label">Teléfono</span>
                                <span class="info-value"><?= htmlspecialchars($cliente['telefono']) ?></span>
                                <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" class="form-control">
                            </div>
                        </div>

                        <div class="profile-info-item">
                            <i class="fas fa-user"></i>
                            <div class="info-content">
                                <span class="info-label">Usuario</span>
                                <span class="info-value"><?= htmlspecialchars($cliente['usuario']) ?></span>
                                <input type="text" name="usuario" value="<?= htmlspecialchars($cliente['usuario']) ?>" class="form-control">
                            </div>
                        </div>

                        <div class="profile-info-item">
                            <i class="fas fa-lock"></i>
                            <div class="info-content">
                                <span class="info-label">Contraseña</span>
                                <span class="info-value">********</span>
                                <input type="password" name="password" placeholder="Nueva contraseña (opcional)" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>">
                    <input type="hidden" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>">

                    <button type="button" class="btn-edit-profile" id="btnEditProfile">
                        <i class="fas fa-edit"></i> Editar Perfil
                    </button>

                    <div class="form-actions hidden" id="profileActions">
                        <button type="submit" class="btn-save">Guardar</button>
                        <button type="button" class="btn-cancel" id="btnCancelProfile">Cancelar</button>
                    </div>
                </form>
            </div>

            <div class="pets-section">
                <div class="pets-header">
                    <h2>Mis Mascotas</h2>
                    <button class="btn-add-pet" onclick="window.location.href='agregar_mascota.php'">
                        <i class="fas fa-plus"></i> Agregar Mascota
                    </button>
                </div>

                <div class="pets-grid">
                    <?php foreach ($mascotas as $index => $mascota): ?>
                    <div class="pet-card" id="petCard<?= $index ?>">
                        <h3><?= htmlspecialchars($mascota['nombre']) ?></h3>

                        <form action="actualizar_perfil.php" method="POST" id="petForm<?= $index ?>">
                            <input type="hidden" name="mascotas[<?= $index ?>][id_mascota]" value="<?= $mascota['id_mascota'] ?>">

                            <div class="pet-info-grid" id="petInfo<?= $index ?>">
                                <div class="pet-info-item">
                                    <span class="label">Especie</span>
                                    <span class="value"><?= htmlspecialchars($mascota['especie']) ?></span>
                                </div>
                                <div class="pet-info-item">
                                    <span class="label">Raza</span>
                                    <span class="value"><?= htmlspecialchars($mascota['raza']) ?></span>
                                </div>
                                <div class="pet-info-item">
                                    <span class="label">Edad</span>
                                    <span class="value"><?= htmlspecialchars($mascota['edad']) ?> años</span>
                                </div>
                                <div class="pet-info-item">
                                    <span class="label">Sexo</span>
                                    <span class="value"><?= htmlspecialchars($mascota['sexo']) ?></span>
                                </div>
                            </div>

                            <div class="form-group hidden" id="petFormFields<?= $index ?>">
                                <label>Nombre</label>
                                <input type="text" name="mascotas[<?= $index ?>][nombre]" value="<?= htmlspecialchars($mascota['nombre']) ?>">

                                <label>Especie</label>
                                <input type="text" name="mascotas[<?= $index ?>][especie]" value="<?= htmlspecialchars($mascota['especie']) ?>">

                                <label>Raza</label>
                                <input type="text" name="mascotas[<?= $index ?>][raza]" value="<?= htmlspecialchars($mascota['raza']) ?>">

                                <label>Edad</label>
                                <input type="number" name="mascotas[<?= $index ?>][edad]" value="<?= htmlspecialchars($mascota['edad']) ?>">

                                <label>Sexo</label>
                                <select name="mascotas[<?= $index ?>][sexo]">
                                    <option value="Macho" <?= $mascota['sexo'] == 'Macho' ? 'selected' : '' ?>>Macho</option>
                                    <option value="Hembra" <?= $mascota['sexo'] == 'Hembra' ? 'selected' : '' ?>>Hembra</option>
                                </select>
                            </div>

                            <div class="pet-actions" id="petActions<?= $index ?>">
                                <button type="button" class="btn-icon btn-edit" onclick="editarMascota(<?= $index ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>

                            <div class="form-actions hidden" id="petFormActions<?= $index ?>">
                                <button type="submit" class="btn-save">Guardar</button>
                                <button type="button" class="btn-cancel" onclick="cancelarMascota(<?= $index ?>)">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <?php endforeach; ?>

                    <div class="add-pet-card" onclick="window.location.href='agregar_mascota.php'">
                        <i class="fas fa-paw"></i>
                        <h3>Agregar una nueva mascota</h3>
                        <p>Registra a tu amigo peludo para mantener su información al día.</p>
                        <button class="btn-add-pet">
                            <i class="fas fa-plus"></i> Agregar Mascota
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Editar Perfil del Cliente
const btnEditProfile = document.getElementById('btnEditProfile');
const profileCard = document.getElementById('profileCard');
const profileActions = document.getElementById('profileActions');
const btnCancelProfile = document.getElementById('btnCancelProfile');

btnEditProfile.addEventListener('click', function() {
    profileCard.classList.add('editing');
    btnEditProfile.classList.add('hidden');
    profileActions.classList.remove('hidden');
});

btnCancelProfile.addEventListener('click', function() {
    profileCard.classList.remove('editing');
    btnEditProfile.classList.remove('hidden');
    profileActions.classList.add('hidden');
    // Recargar para restaurar valores originales
    location.reload();
});

// Editar Mascota
function editarMascota(index) {
    const petInfo = document.getElementById('petInfo' + index);
    const petFormFields = document.getElementById('petFormFields' + index);
    const petActions = document.getElementById('petActions' + index);
    const petFormActions = document.getElementById('petFormActions' + index);

    petInfo.classList.add('hidden');
    petFormFields.classList.remove('hidden');
    petActions.classList.add('hidden');
    petFormActions.classList.remove('hidden');
}

function cancelarMascota(index) {
    const petInfo = document.getElementById('petInfo' + index);
    const petFormFields = document.getElementById('petFormFields' + index);
    const petActions = document.getElementById('petActions' + index);
    const petFormActions = document.getElementById('petFormActions' + index);

    petInfo.classList.remove('hidden');
    petFormFields.classList.add('hidden');
    petActions.classList.remove('hidden');
    petFormActions.classList.add('hidden');
    // Recargar para restaurar valores originales
    location.reload();
}
</script>

<?php
// 6. Incluir el footer (subiendo un nivel con ../)
include '../includes/plantilla_footer.php';
?>