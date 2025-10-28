<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Patitas Felices</title>
    <link rel="stylesheet" href="./css/inicio.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>
    <header class="header">
        <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices"class="logo">
        <h1>Clínica Veterinaria Patitas Felices</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="#">Inicio</a></li>
                <li><a href="propietario/perfil.php">Perfil</a></li>
                
                <li class="dropdown">
                    <button class="dropbtn">Más Opciones</button>
                    <div class="dropdown-content">
                        <a href="citas/regicita.php">Registrar Cita</a>
                        <a href="citas/ver_citas.php">Ver Citas</a>
                        <a href="historial/historial.php">Historial Clínico</a>
                        <a href="contacto.php">Contacto</a>
                        <a href="includes/logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>


    <img src="https://res.cloudinary.com/zacwillmington/image/upload/v1495768121/jordi_rcf42n.jpg"
    width="100%"
  height="750"> 
<section>
    <main class="main-content">
        <h2>Bienvenido a Patitas Felices</h2>
        <p>En nuestra clínica, el bienestar de tus mascotas es lo más importante. Ofrecemos servicios de consulta, vacunación, estética, cirugías menores y mucho más.</p>
        <p>¡Gracias por confiar en nosotros para cuidar a tu mejor amigo!</p>
    </main>

    <main class="main-content">
        <h3>Atención Médica Veterinaria</h3>
        <p>En nuestra clínica brindamos un cuidado profesional y completo para las mascotas. Ofrecemos consultas generales, vacunación y desparasitación, adaptadas a las necesidades de cada animal. </p>
        <p>También realizamos cirugías menores con personal calificado y equipo seguro. En caso de imprevistos, contamos con atención de urgencias veterinarias para responder rápidamente ante cualquier situación crítica.
        </p>
    </main>


    <main class="main-content">
        <h3>Cuidado y Bienestar</h3>
        <p>
            Nos preocupamos por la salud integral de las mascotas, por eso ofrecemos servicios de estética y baño, ayudando a mantener su higiene y comodidad. 
        </p>
        <p>Además, disponemos de una tienda de productos veterinarios, donde se pueden encontrar alimentos especializados, accesorios y medicamentos de uso común, siempre con el respaldo de marcas confiables.
        </p>
    </main>
</section>

<section class="image-gallery">
    <h3>Nuestras Mascotas Felices</h3>
    <div class="image-container fade-in">
        <img src="http://res.cloudinary.com/zacwillmington/image/upload/v1495768190/rabbit_bescwn.jpg" alt="Mascota feliz">
    </div>
    <div class="image-container fade-in">
        <img src="http://res.cloudinary.com/zacwillmington/image/upload/v1495768155/pug_wttz9k.jpg" alt="Mascota jugando">
    </div>
</section>


    <footer class="footer">
        <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    </footer>

    <script src="./js/scrini.js"></script>
</body>
</html>


