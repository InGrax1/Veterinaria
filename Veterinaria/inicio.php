<?php
// 1. Definir variables para la plantilla
$page_title = "Inicio - Patitas Felices";
$page_css = "/Veterinaria/Veterinaria/css/inicio.css"; // Ruta absoluta CORREGIDA

// 2. Incluir el header
include 'includes/plantilla_header.php';
?>

<main>
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-text">
                <h1>Bienvenidos a Patitas Felices, donde cuidamos a tu mejor amigo</h1>
                <p>Comprometidos con la salud y felicidad de tu mascota en cada etapa de su vida.</p>
                <div class="hero-buttons">
                    <a href="/Veterinaria/Veterinaria/citas/regicita.php" class="btn btn-primary">Agenda una Cita</a>
                    <a href="#servicios" class="btn btn-secondary">Nuestros Servicios</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://media.istockphoto.com/id/1503385646/es/foto/retrato-divertido-y-feliz-perro-cachorro-shiba-inu-asom%C3%A1ndose-detr%C3%A1s-de-una-pancarta-azul.jpg?s=612x612&w=0&k=20&c=mMZ1Dvy0J8iNpF2boWkL8bo45vYYPi_AvZ1aYr9oO8w=" alt="Cachorro feliz">
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
                <img src="https://img.freepik.com/vector-gratis/ejemplo-lindo-vector-historieta-salmon-sushi-gato_138676-2230.jpg" alt="Gato descansando">
                <h3>Tienda de Productos</h3>
                <p>Encuentra todo lo que necesitas para el bienestar de tu compañero en nuestra clínica.</p>
            </div>
        </div>
    </section>

</main>

<script src="/Veterinaria/Veterinaria/js/scrini.js"></script> <?php
// 5. Incluir el footer
include 'includes/plantilla_footer.php';
?>