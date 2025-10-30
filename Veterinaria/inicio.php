<?php
// Aquí puedes poner tu lógica de sesión si es necesario, ej:
// session_start();
// if (!isset($_SESSION['usuario'])) {
//     header("Location: login/login.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Patitas Felices</title>
    <link rel="stylesheet" href="css/inicio.css"> 
    
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <li><a href="#">Inicio</a></li>
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

    <main>
        <section class="hero">
            <div class="container hero-container">
                <div class="hero-text">
                    <h1>Bienvenidos a Patitas Felices, donde cuidamos a tu mejor amigo</h1>
                    <p>Comprometidos con la salud y felicidad de tu mascota en cada etapa de su vida.</p>
                    <div class="hero-buttons">
                        <a href="citas/regicita.php" class="btn btn-primary">Agenda una Cita</a>
                        <a href="#servicios" class="btn btn-secondary">Nuestros Servicios</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://i.imgur.com/v82p6fS.png" alt="Cachorro feliz">
                </div>
            </div>
        </section>

        <section id="servicios" class="services">
            <div class="container">
                <h2>Nuestros Servicios Veterinarios</h2>
                <div class="services-grid">
                    
                    <article class="service-card">
                        <i class='bx bx-plus-medical service-icon'></i>
                        <h3>Consultas</h3>
                        <p>Atención médica completa para el diagnóstico y tratamiento.</p>
                    </article>
                    
                    <article class="service-card">
                        <i class='bx bx-injection service-icon'></i>
                        <h3>Vacunación</h3>
                        <p>Protege a tu mascota con un plan de vacunación.</p>
                    </article>
                    
                    <article class="service-card">
                        <i class='bx bx-cut service-icon'></i>
                        <h3>Estética</h3>
                        <p>Servicios de baño y estética para que luzcan geniales.</p>
                    </article>
                    
                    <article class="service-card">
                        <i class='bx bx-pulse service-icon'></i>
                        <h3>Cirugías Menores</h3>
                        <p>Procedimientos quirúrgicos seguros y de rápida recuperación.</p>
                    </article>
                    
                    <article class="service-card">
                        <i class='bx bx-first-aid service-icon'></i>
                        <h3>Urgencias</h3>
                        <p>Atención inmediata para emergencias médicas.</p>
                    </article>

                </div>
            </div>
        </section>

        <section class="care">
            <div class="container care-container">
                <div class="care-text">
                    <h2>Cuidado y Bienestar Integral</h2>
                    <p>Creemos en la prevención como la mejor medicina. Además de nuestros servicios clínicos, ofrecemos una tienda con productos de alta calidad para el cuidado diario de tu mascota, desde alimentos especializados hasta juguetes y accesorios.</p>
                </div>
                <div class="care-image-box">
                    <img src="https://i.imgur.com/fD0mSjB.png" alt="Gato descansando">
                    <h3>Tienda de Productos</h3>
                    <p>Encuentra todo lo que necesitas para el bienestar de tu compañero en nuestra clínica.</p>
                </div>
            </div>
        </section>

    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Clínica Veterinaria Patitas Felices © 2025</p>
            <p>Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
        </div>
    </footer>

    <script src="./js/scrini.js"></script>
</body>
</html>