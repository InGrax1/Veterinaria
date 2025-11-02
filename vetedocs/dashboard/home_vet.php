<?php
session_start();

// Verificar si está logueado
if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}

include '../includes/scriptdb.php';

// Determinar sección activa
$seccion = $_GET['seccion'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Vet - Panel de Administración</title>
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <i class="fas fa-paw" style="color: #f09e2b;"></i>
            <span class="brand-name">Clínica Vet</span>
        </div>
        <div class="header-right">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Buscar paciente o veterinario">
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <?= substr($_SESSION['veterinario_nombre'], 0, 2) ?>
                </div>
                <div class="user-details">
                    <h4><?php echo htmlspecialchars($_SESSION['veterinario_nombre']); ?></h4>
                    <p>Administrador</p>
                </div>
            </div>
            <a href="../logout.php" class="logout-btn">Salir</a>
        </div>
    </header>

    <!-- Dashboard Layout -->
    <div class="dashboard-layout">
        <!-- Menú lateral -->
        <aside class="sidebar">
            <div class="menu-section-title">Principal</div>
            <a href="?seccion=dashboard" class="menu-item <?= $seccion === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-line" style="color: #f09e2b;"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="?seccion=pacientes" class="menu-item <?= $seccion === 'pacientes' ? 'active' : '' ?>">
                <i class="fas fa-paw" style="color: #f09e2b;"></i>
                <span>Pacientes</span>
            </a>
            
            <div class="menu-divider"></div>
            
            <div class="menu-section-title">Administración</div>
            <a href="?seccion=veterinarios" class="menu-item <?= $seccion === 'veterinarios' ? 'active' : '' ?>">
                <i class="fas fa-user-md" style="color: #f09e2b;"></i>
                <span>Veterinarios</span>
            </a>
            
            <?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])): ?>
                <a href="?seccion=registrar_vet" class="menu-item <?= $seccion === 'registrar_vet' ? 'active' : '' ?>">
                    <i class="fas fa-plus" style="color: #f09e2b;"></i>
                    <span>Nuevo Veterinario</span>
                </a>
            <?php endif; ?>

            <div class="menu-divider"></div>
            
            <div class="menu-section-title">Soporte</div>
            <a href="#" class="menu-item">
               <i class="fas fa-file-medical" style="color: #f09e2b;"></i>
                <span>Ayuda</span>
            </a>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <?php
            // Cargar sección correspondiente
            switch ($seccion) {
                case 'dashboard':
                    include 'secciones/dashboard_seccion.php';
                    break;
                case 'pacientes':
                    include 'secciones/pacientes_seccion.php';
                    break;
                case 'veterinarios':
                    include 'secciones/veterinarios_seccion.php';
                    break;
                case 'registrar_vet':
                    if (in_array($_SESSION['veterinario_id'], [1, 2, 3])) {
                        include 'secciones/registrar_vet_seccion.php';
                    }
                    break;
                default:
                    include 'secciones/dashboard_seccion.php';
            }
            ?>
        </main>
    </div>
</body>
</html>