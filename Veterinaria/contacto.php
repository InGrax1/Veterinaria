<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Veterinaria</title>
    <link rel="stylesheet" href="css/contacto.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
                    <li><a href="inicio.php">Inicio</a></li>
                    <li><a href="propietario/perfil.php">Perfil</a></li>
                    <li class="dropdown">
                        <button class="dropbtn">Más Opciones</button>
                        <div class="dropdown-content">
                            <a href="citas/regicita.php">Registrar Cita</a>
                            <a href="citas/ver_citas.php">Ver Citas</a>
                            <a href="historial/historial.php">Historial Clínico</a>
                            <a href="contacto.php">Contacto</a>
                            <a href="includes/logout.php" class="logout">Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="page-content">
        <div class="form-container">
            <h2>Contáctanos</h2>
            <p>Envíanos un mensaje si tienes dudas o comentarios.</p>

            <div class="contact-info">
                <h3>Clínica Veterinaria Patitas Felices</h3>
                <p><strong>Teléfono:</strong> (123) 456-7890</p>
                <p><strong>Email:</strong> contacto@patitasfelices.com</p>
                <p><strong>Dirección:</strong> Calle 123, Ciudad Animalia</p>
            </div>

            <form action="https://formspree.io/f/mrbqarwd" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre completo" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="email" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                </div>

                <button type="submit" class="btn-primary">Enviar mensaje</button>
            </form>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Clínica Veterinaria Patitas Felices © 2025</p>
            <p>Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
        </div>
    </footer>

</body>
</html>