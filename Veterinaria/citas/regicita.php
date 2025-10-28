<?php
    include '../includes/header.php'; // Esto ya incluye session_start y validación

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



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cita - Patitas Felices</title>
    <link rel="stylesheet" href="../css/regicita.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>


<body>
    <header class="header">
        <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
        <h1>Clínica Veterinaria Patitas Felices</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="../inicio.php">Inicio</a></li>
                <li><a href="../propietario/perfil.php">Perfil</a></li>
                
                <li class="dropdown">
                    <button class="dropbtn">Más Opciones</button>
                    <div class="dropdown-content">
                        <a href="#">Registrar Cita</a>
                        <a href="ver_citas.php">Ver Citas</a>
                        <a href="../historial/historial.php">Historial Clínico</a>
                        <a href="../contacto.php">Contacto</a>
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

    <form id="form-si" action="../includes/registrar_cita.php" method="POST" style="display: none;">
        <label for="mascota-select">Selecciona tu mascota:</label>
        <select id="mascota-select" name="mascota_id" required>
            <option value="">--Selecciona--</option>
            <?php foreach ($mascotas as $mascota): ?>
                <option value="<?= $mascota['id_mascota'] ?>"><?= htmlspecialchars($mascota['nombre']) ?></option>
            <?php endforeach; ?>
        </select>


            <label for="fecha">Fecha de la Cita:</label>
            <input type="date" id="fecha" name="fecha" required>

            <label for="hora">Hora de la Cita:</label>
            <input type="time" id="hora" name="hora" step="1800" min="08:00" max="20:00" required>

         <label for="motivo">Motivo:</label>
        <textarea id="motivo" name="motivo" placeholder="Ejemplo: Vacunación, Consulta" required></textarea>

            <button type="submit" class="btn">Registrar Cita</button>
     </form>






    <!-- Formulario si la mascota NO está registrada -->

    <form id="form-no" action="../includes/registrar_mascota.php" method="POST" style="display: none;">
    <!-- Datos mascota -->

    <label for="nombre">Nombre de la Mascota:</label>
    <input type="text" id="nombre" name="nombre" placeholder="Ejemplo: Max" required>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" placeholder="Ejemplo: 3 años" required>

    <label for="especie">Especie:</label>
    <input type="text" id="especie" name="especie" placeholder="Ejemplo: Perro, Gato" required>

    <label for="raza">Raza:</label>
    <input type="text" id="raza" name="raza" placeholder="Ejemplo: Labrador" required>

    <label for="sexo">Sexo:</label>
    <select id="sexo" name="sexo" required>
        <option value="">--Selecciona--</option>
        <option value="Macho">Macho</option>
        <option value="Hembra">Hembra</option>
    </select>

        <!-- Datos cita -->
        <label for="fecha2">Fecha de la Cita:</label>
        <input type="date" id="fecha2" name="fecha" required>

        <label for="hora2">Hora de la Cita:</label>
        <input type="time" id="hora2" name="hora" step="1800" min="08:00" max="20:00" required>

        <label for="motivo2">Motivo:</label>
        <textarea id="motivo2" name="motivo" placeholder="Ejemplo: Vacunación, Consulta" required></textarea>

        <button type="submit" class="btn">Registrar Cita</button>
    </form>

</div>


    </main>

    <footer class="footer">
        <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const formSi = document.getElementById("form-si");
        const formNo = document.getElementById("form-no");

        document.querySelectorAll('.opcion-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const opcion = this.textContent.trim().toLowerCase();

                if (opcion === "sí" || opcion === "si") {
                    formSi.style.display = "block";
                    formNo.style.display = "none";
                } else if (opcion === "no") {
                    formSi.style.display = "none";
                    formNo.style.display = "block";
                }
            });
        });
    });
    </script>



</body>
</html>
