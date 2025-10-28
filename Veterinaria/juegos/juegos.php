<?php
session_start();
if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Juegos - Patitas Felices</title>
    <link rel="stylesheet" href="../css/juegos.css">
</head>
<body>

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

    <main class="contenedor-juegos">
        <h2>Juegos para pasar un buen rato</h2>
        <div class="tarjetas-juego">
            <div class="juego-card">
                <img src="https://play-lh.googleusercontent.com/42n_hBKxY51WipHOIDJ-rMZmnZo2lI0wN-qc4_2DQWVosfZRrl5ESil9drZSPPOHgJs" alt="Juego 1">
                <h3>🐶 Perro Pastor</h3>
                <p>¡Conviértete en un perro pastor! Guía a todas las ovejas hacia el centro antes de que se escapen.</p>
                <a href="juego1.php" class="boton-jugar">Jugar</a>
            </div>
            <div class="juego-card">
                <img src="https://i.ytimg.com/vi/qMBl6OhrdEI/maxresdefault.jpg" alt="Juego 2">
                <h3>🐾 Nyan Pong</h3>
                <p>Controla a Nyan Cat en una batalla de reflejos. ¡Juega solo o reta a un amigo y evita que la pelota salga de tu lado!</p>
                <a href="juego2.php" class="boton-jugar">Jugar</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
    </footer>
    
</body>
</html>
