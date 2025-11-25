<?php
// 1. Definir variables para la plantilla
$page_title = "Agregar Mascota - Patitas Felices";
$page_css = "/Veterinaria/Veterinaria/css/regicita.css"; // Reutilizamos el mismo CSS

// 2. Incluir el header
include '../includes/plantilla_header.php';

// 3. Lógica específica de esta página
include '../includes/scriptdb.php';

$id_propietario = $_SESSION['id_propietario'];
?>

<main class="page-content">
    <div class="form-container">
        <h2>Agregar Nueva Mascota</h2>
        <p>Registra a tu nueva mascota completando los siguientes datos.</p>

        <form action="procesar_nueva_mascota.php" method="POST" class="form-grid">
            
            <div class="form-group">
                <label for="nombre">Nombre de la Mascota:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ejemplo: Max" required>
            </div>

            <div class="form-group">
                <label for="edad">Edad (años):</label>
                <input type="number" id="edad" name="edad" placeholder="Ejemplo: 3" min="0" max="30" required>
            </div>

            <div class="form-group">
                <label for="especie">Especie:</label>
                <select id="especie" name="especie" required>
                    <option value="">--Selecciona--</option>
                    <option value="Perro">Perro</option>
                    <option value="Gato">Gato</option>
                    <option value="Ave">Ave</option>
                    <option value="Conejo">Conejo</option>
                    <option value="Hamster">Hámster</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="raza">Raza:</label>
                <input type="text" id="raza" name="raza" placeholder="Ejemplo: Labrador, Siamés" required>
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
                <label for="color">Color/Descripción (opcional):</label>
                <input type="text" id="color" name="color" placeholder="Ejemplo: Café con blanco">
            </div>

            <div class="form-actions span-two">
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-paw"></i> Registrar Mascota
                </button>
                <a href="perfil.php" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>

    </div>
</main>

<style>
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-cancel {
        background-color: #6b7280;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s ease;
    }

    .btn-cancel:hover {
        background-color: #4b5563;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .span-two {
        grid-column: 1 / -1;
    }
</style>

<?php
include '../includes/plantilla_footer.php';
?>