<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cita - Patitas Felices</title>
    <link rel="stylesheet" href="../css/regicita.css">
</head>


<body>
    <header class="header">
        <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
        <h1>Clínica Veterinaria Patitas Felices</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="../inicio.php">Inicio</a></li>
                <li><a href="#">Perfil</a></li>
                
                <li class="dropdown">
                    <button class="dropbtn">Más Opciones</button>
                    <div class="dropdown-content">
                        <a href="#">Registrar Cita</a>
                        <a href="#">Cancelar Cita</a>
                        <a href="#">Historial Clínico</a>
                        <a href="#">Juegos</a>
                        <a href="#">Configuración</a>
                        <a href="../includes/logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <h2>Registrar Cita</h2>
        <p>Por favor, completa los siguientes campos para agendar una cita para tu mascota.</p>

        <div class="appointment-form">
    <label for="mascota-registrada">¿Tu mascota ya está registrada?</label>
    <div class="opciones-registro">
        <button type="button" class="btn opcion-btn" onclick="mostrarFormulario('si')">Sí</button>
        <button type="button" class="btn opcion-btn" onclick="mostrarFormulario('no')">No</button>
    </div>

    

    <!-- Formulario si la mascota está registrada -->
    <form id="form-si" style="display: none;">
        <label for="mascota-select">Selecciona tu mascota:</label>
        <select id="mascota-select" required>
            <option value="">--Selecciona--</option>
            <option value="1">Max</option>
            <option value="2">Luna</option>
            <!-- Estos valores se rellenarán dinámicamente con PHP más adelante -->
        </select>

        <label for="fecha">Fecha de la Cita:</label>
        <input type="date" id="fecha" required>

        <label for="hora">Hora de la Cita:</label>
        <input type="time" id="hora" name="hora" step="1800" required>

        <label for="motivo">Motivo:</label>
        <textarea id="motivo" placeholder="Ejemplo: Vacunación, Consulta" required></textarea>

        <button type="submit" class="btn">Registrar Cita</button>
    </form>




    <!-- Formulario si la mascota NO está registrada -->
    <form id="form-no" style="display: none;">
        <label for="nombre">Nombre de la Mascota:</label>
        <input type="text" id="nombre" placeholder="Ejemplo: Max" required>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" placeholder="Ejemplo: 3 años" required>

        <label for="especie">Especie:</label>
        <input type="text" id="especie" placeholder="Ejemplo: Perro, Gato" required>

        <label for="raza">Raza:</label>
        <input type="text" id="raza" placeholder="Ejemplo: Labrador" required>

        <label for="sexo">Sexo:</label>
        <select id="sexo" required>
            <option value="">--Selecciona--</option>
            <option value="Macho">Macho</option>
            <option value="Hembra">Hembra</option>
        </select>

        <label for="fecha2">Fecha de la Cita:</label>
        <input type="date" id="fecha2" required>

        <label for="hora2">Hora de la Cita:</label>
        <input type="time" id="hora" name="hora" step="1800" min="08:00" max="20:00" required>


        <label for="motivo2">Motivo:</label>
        <textarea id="motivo2" placeholder="Ejemplo: Vacunación, Consulta" required></textarea>

        <button type="submit" class="btn">Registrar Cita</button>
    </form>
</div>


    </main>

    <footer class="footer">
        <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    </footer>

    <script>
function mostrarFormulario(opcion) {
    const formSi = document.getElementById("form-si");
    const formNo = document.getElementById("form-no");

    if (opcion === "si") {
        formSi.style.display = "block";
        formNo.style.display = "none";
    } else if (opcion === "no") {
        formSi.style.display = "none";
        formNo.style.display = "block";
    }
}
</script>


</body>
</html>
