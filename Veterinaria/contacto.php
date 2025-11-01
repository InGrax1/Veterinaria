<?php
// 1. Definir variables para la plantilla
$page_title = "Contacto - Patitas Felices";
$page_css = "/Veterinaria/Veterinaria/css/contacto.css"; // Ruta absoluta al CSS de esta página

// 2. Incluir el header
// La lógica de sesión ya está en el header.
include 'includes/plantilla_header.php';
?>

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

<?php
// 4. Incluir el footer
include 'includes/plantilla_footer.php';
?>