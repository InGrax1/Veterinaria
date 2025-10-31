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

    <header class="site-header">
        <div class="container">
            <div class="logo">
                <span class="logo-icon">🐾</span>
                Patitas Felices
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="../inicio.php">Inicio</a></li>
                    <li><a href="../propietario/perfil.php">Perfil</a></li>
                    
                    <li class="dropdown">
                        <button class="dropbtn">Más Opciones</button>
                        <div class="dropdown-content">
                            <a href="regicita.php">Registrar Cita</a>
                            <a href="ver_citas.php">Ver Citas</a>
                            <a href="../historial/historial.php">Historial Clínico</a>
                            <a href="../contacto.php">Contacto</a>
                            <a href="../includes/logout.php" class="logout">Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

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

            <form id="form-si" action="../includes/registrar_cita.php" method="POST" style="display: none;">
                
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

            <form id="form-no" action="../includes/registrar_mascota.php" method="POST" style="display: none;" class="form-grid">
                
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

    <footer class="site-footer">
        <div class="container">
            <p>Clínica Veterinaria Patitas Felices © 2025</p>
            <p>Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
        </div>
    </footer>

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
            // Esta lógica es más robusta:
            opcionBotones.forEach(btn => {
                // Extraemos la opción (ej. 'si' o 'no') del atributo 'onclick' de CADA botón
                const btnOnClick = btn.getAttribute('onclick');
                
                // Si el 'onclick' de este botón contiene la opción que se presionó (ej. 'si')
                if (btnOnClick && btnOnClick.includes("'" + opcion + "'")) {
                    btn.classList.add('active'); // Se activa
                } else {
                    btn.classList.remove('active'); // Se desactiva
                }
            });
        }
        
        // YA NO NECESITAMOS el "addEventListener" aquí
        // porque el 'onclick=""' en el HTML ya hace el trabajo.

    });
    </script>

</body>
</html>