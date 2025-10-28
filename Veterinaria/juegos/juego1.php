<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Juego Campo de Ovejas - Patitas Felices</title>

  <link rel="stylesheet" href="../css/juego1.css">
  <link rel="stylesheet" href="../css/juego12.css">
  

 <header class="header">
        <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
        <h1>Clínica Veterinaria Patitas Felices</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="../inicio.php">Inicio</a></li>
                <li><a href="#">Perfil</a></li>
                <li class="dropdown">
                    <button class="dropbtn">Más Opciones</button>
                    <div class="dropdown-content">
                        <a href="../citas/regicita.php">Registrar Cita</a>
                        <a href="../citas/ver_citas.php">Ver Citas</a>
                        <a href="#">Historial Clínico</a>
                        <a href="#">Juegos</a>
                        <a href="#">Configuración</a>
                        <a href="../includes/logout.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

  
</head>

  

<body>

 



  <section class="field" id="field">
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>
    <div class="sheep-zone"><div class="sheep"></div></div>

    <div class="sheep-pen"></div>

    <div id="dog" class="top">
      <div class="doghead"></div>
      <div class="dogears"></div>
      <div class="dogtail"></div>
    </div>

    <div id="info" class="menu"><span>?</span></div>
    <div id="trap" class="menu"></div>
    <div id="lost" class="menu"></div>
    <div id="restart" class="menu"><a target="_top" href="game.html"><span></span></a></div>
    <div id="msg"></div>
  </section>

  <script src="../js/scriptj1.js"></script>

  <footer class="footer">
        <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    </footer>
</body>
</html>
