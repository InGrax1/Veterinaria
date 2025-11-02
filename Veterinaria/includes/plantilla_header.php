<?php
// 1. Lógica de sesión
session_start();
$tiempo_inactividad = 3600; 

// Control de inactividad solo si hay sesión activa
if (isset($_SESSION['usuario'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $tiempo_inactividad)) {
        session_unset();
        session_destroy();
        header("Location: /Veterinaria/Veterinaria/login/index.php?timeout=1");
        exit();
    }
    $_SESSION['last_activity'] = time();
}

// Variable para saber si el usuario está logueado y qué tipo es
$usuario_logueado = isset($_SESSION['usuario']);
$es_veterinario = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'veterinario';
$es_propietario = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'propietario';

// 2. Variables para que cada página defina su título y CSS
if (!isset($page_title)) {
    $page_title = "Patitas Felices";
}
if (!isset($page_css)) {
    // Ruta absoluta por defecto
    $page_css = "/Veterinaria/Veterinaria/css/inicio.css"; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <link rel="stylesheet" href="<?php echo htmlspecialchars($page_css); ?>"> 
    
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li><a href="/Veterinaria/Veterinaria/inicio.php">Inicio</a></li>
                
                <?php if ($es_propietario): ?>
                    <!-- Menú completo para PROPIETARIOS (clientes) -->
                    <li><a href="/Veterinaria/Veterinaria/propietario/perfil.php">Perfil</a></li>
                    
                    <li class="dropdown">
                        <button class="dropbtn">Más Opciones</button>
                        <div class="dropdown-content">
                            <a href="/Veterinaria/Veterinaria/citas/regicita.php">Registrar Cita</a>
                            <a href="/Veterinaria/Veterinaria/citas/ver_citas.php">Ver Citas</a>
                            <a href="/Veterinaria/Veterinaria/historial/historial.php">Historial Clínico</a>
                            <a href="/Veterinaria/Veterinaria/contacto.php">Contacto</a>
                            <a href="/Veterinaria/Veterinaria/includes/logout.php" class="logout">Cerrar sesión</a>
                        </div>
                    </li>
                    
                <?php elseif ($es_veterinario): ?>
                    <!-- Menú limitado para VETERINARIOS -->
                    <li><a href="/Veterinaria/Veterinaria/contacto.php">Contacto</a></li>
                    
                    <li class="dropdown">
                        <button class="dropbtn">Mi Panel</button>
                        <div class="dropdown-content">
                            <a href="/Veterinaria/vetedocs/dashboard/home_vet.php">🏥 Panel Veterinario</a>
                            <a href="/Veterinaria/Veterinaria/includes/logout.php" class="logout">Cerrar sesión</a>
                        </div>
                    </li>
                    
                <?php else: ?>
                    <!-- Menú para visitantes no logueados -->
                    <li><a href="/Veterinaria/Veterinaria/contacto.php">Contacto</a></li>
                    <li><a href="/Veterinaria/Veterinaria/login/index.php" class="btn-login-header">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>