<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Gatito</title>
    <link rel="stylesheet" href="../css/stilogin.css">
    
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>

</head>
<body>


    <form method="POST" action="login.php">    
    <div class="background-animation"></div> <!-- Animación de fondo -->
    <div class="login-container">

        <img 
        src="https://i.pinimg.com/236x/05/49/86/05498664d54894f92c6523c50c1eb9e6.jpg" 
        alt="Gatito" 
        width="75" height="75">

        <h2>INICIAR SESIÓN</h2>

        
        <input type="text" name="usuario" placeholder="Usuario" class="input" required> 
                
                <div class="password-container">
        <input type="password" name="password" id="password" placeholder="Contraseña" class="input" required>
                </div>
        <button class="btn">Ingresar</button>
        <p class="sign-up">¿No tienes cuenta? <a href="../registro/registro.php">Regístrate aquí</a></p>



    </div>
    <script src="../js/script.js"></script>


    <footer class="footer">
    <p>Clínica Veterinaria Patitas Felices</p>
    <p>Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    <p>Dirección: Calle 123, Ciudad Animalia</p>
</footer>

</body>
</html>
