<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Veterinaria</title>
    
    <link rel="stylesheet" href="/Veterinaria/Veterinaria/css/contacto.css"> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
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
        <div class="container">
            <div class="contact-container">
            <div class="contact-info-left">
                <h1>¡Hablemos!</h1>
                <p>Estamos aquí para ayudarte con cualquier pregunta que tengas. Rellena el formulario y nos pondremos en contacto contigo lo antes posible.</p>
                
                <div class="direct-contact-card">
                    <h3>Contacto Directo</h3>
                    <p class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>(123) 456-7890</span>
                    </p>
                    <p class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Calle 123, Ciudad Animalia</span>
                    </p>
                    <p class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contacto@patitasfelices.com</span>
                    </p>
                </div>
            </div>

            <div class="contact-form-right">
                <form action="https://formspree.io/f/mrbqarwd" method="POST" class="contact-form-card">
                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre completo" required>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="email" placeholder="ejemplo@correo.com" required>
                    </div>

                    <div class="form-group">
                        <label for="asunto">Asunto</label>
                        <input type="text" id="asunto" name="asunto" placeholder="Consulta general, cita, etc.">
                    </div>

                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Enviar Mensaje</button>
                
                </form>
            </div>
        </div>
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