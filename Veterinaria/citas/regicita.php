<?php
// 1. Definir variables para la plantilla
$page_title = "Registrar Cita - Patitas Felices";
$page_css = "/Veterinaria/Veterinaria/css/regicita.css"; // Ruta absoluta al CSS

// 2. Incluir el header (subiendo un nivel con ../)
// La plantilla ya incluye la lógica de sesión de 'includes/header.php'
include '../includes/plantilla_header.php';

// 3. Lógica específica de esta página
include '../includes/scriptdb.php'; // Conexión a la base de datos

$id_propietario = $_SESSION['id_propietario'];

// Obtenemos las mascotas del propietario
$sql = "SELECT id_mascota, nombre FROM mascota WHERE id_propietario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_propietario);
$stmt->execute();
$result = $stmt->get_result();

$mascotas = [];
while ($row = $result->fetch_assoc()) {
    $mascotas[] = $row;
}

$stmt->close();
$conn->close();
?>

<main class="page-content">
    <div class="form-container">
        <h2>Registrar Cita</h2>
        <p>Completa los campos para agendar una cita para tu mascota.</p>

        <div class="selection-prompt">
            <label>¿Tu mascota ya está registrada?</label>
            <div class="opciones-registro">
                <button type="button" class="opcion-btn" onclick="mostrarFormulario('si')">Sí</button>
                <button type="button" class="opcion-btn" onclick="mostrarFormulario('no')">No</button>
            </div>
        </div>

        <form id="form-si" action="/Veterinaria/Veterinaria/includes/registrar_cita.php" method="POST" style="display: none;">
            
            <div class="form-group">
                <label for="mascota-select">Selecciona tu mascota:</label>
                <select id="mascota-select" name="mascota_id" required>
                    <option value="">--Selecciona--</option>
                    <?php foreach ($mascotas as $mascota): ?>
                        <option value="<?= $mascota['id_mascota'] ?>"><?= htmlspecialchars($mascota['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="fecha">Fecha de la Cita:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora de la Cita:</label>
                    <input type="time" id="hora" name="hora" step="1800" min="08:00" max="20:00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="motivo">Motivo:</label>
                <textarea id="motivo" name="motivo" placeholder="Ejemplo: Vacunación, Consulta" required></textarea>
            </div>

            <button type="submit" class="btn btn-submit">Registrar Cita</button>
        </form>

        <form id="form-no" action="/Veterinaria/Veterinaria/includes/registrar_mascota.php" method="POST" style="display: none;" class="form-grid">
            
            <div class="form-group">
                <label for="nombre">Nombre de la Mascota:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ejemplo: Max" required>
            </div>
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" placeholder="Ejemplo: 3 años" required>
            </div>
            <div class="form-group">
                <label for="especie">Especie:</label>
                <input type="text" id="especie" name="especie" placeholder="Ejemplo: Perro, Gato" required>
            </div>
            <div class="form-group">
                <label for="raza">Raza:</label>
                <input type="text" id="raza" name="raza" placeholder="Ejemplo: Labrador" required>
            </div>
            <div class="form-group">
                <label for="sexo">Sexo:</label>
                <select id="sexo" name="sexo" required>
                    <option value="">--Selecciona--</option>
                    <option value="Macho">Macho</option>
                    <option value="Hembra">Hembra</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha2">Fecha de la Cita:</label>
                <input type="date" id="fecha2" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="hora2">Hora de la Cita:</label>
                <input type="time" id="hora2" name="hora" step="1800" min="08:00" max="20:00" required>
            </div>
            
            <div class="form-group span-two">
                <label for="motivo2">Motivo:</label>
                <textarea id="motivo2" name="motivo" placeholder="Ejemplo: Vacunación, Consulta" required></textarea>
            </div>

            <button type="submit" class="btn btn-submit span-two">Registrar Mascota y Cita</button>
        </form>

    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const formSi = document.getElementById("form-si");
    const formNo = document.getElementById("form-no");
    const opcionBotones = document.querySelectorAll('.opcion-btn');

    // Hacemos la función global para que la llame el 'onclick' del HTML
    window.mostrarFormulario = function(opcion) {
        
        // 1. Muestra el formulario correcto
        if (opcion === "si") {
            formSi.style.display = "block";
            formNo.style.display = "none";
        } else if (opcion === "no") {
            formSi.style.display = "none";
            formNo.style.display = "block";
        }
        
        // 2. Asigna la clase 'active' al botón correcto
        opcionBotones.forEach(btn => {
            const btnOnClick = btn.getAttribute('onclick');
            if (btnOnClick && btnOnClick.includes("'" + opcion + "'")) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }
});
</script>

<?php
// 6. Incluir el footer (subiendo un nivel con ../)
include '../includes/plantilla_footer.php';
?>