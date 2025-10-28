<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Contacto - Veterinaria</title>
  <link rel="stylesheet" href="./css/contacto.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

  <!-- Header -->
  <header class="header">
    <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
    <h1>Clínica Veterinaria Patitas Felices</h1>
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
            <a href="#">Contacto</a>
            <a href="includes/logout.php">Cerrar sesión</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Contact Form -->
  <div class="contact-form">
    <h2>Contáctanos</h2>
    <form action="https://formspree.io/f/mrbqarwd" method="POST">
      <div class="form-group">
        
        <h3>Clínica Veterinaria Patitas Felices</h3>
        Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com
        <br>Dirección: Calle 123, Ciudad Animalia

        <br><br><br>
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

  <!-- Footer -->
  <footer class="footer">
    Veterinaria &copy; 2025 | Todos los derechos reservados
  </footer>

</body>
</html>
