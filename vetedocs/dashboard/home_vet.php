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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f7fa;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: #f09e2b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            padding: 10px 15px 10px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            width: 300px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #f09e2b;
            box-shadow: 0 0 0 3px rgba(240, 158, 43, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 18px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f09e2b, #fbbf24);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-details h4 {
            font-size: 14px;
            color: #2d3748;
            margin-bottom: 2px;
        }

        .user-details p {
            font-size: 12px;
            color: #718096;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Layout principal */
        .dashboard-layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* Menú lateral */
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            padding: 25px 0;
        }

        .menu-section-title {
            padding: 0 20px;
            font-size: 11px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .menu-item {
            padding: 12px 20px;
            color: #4a5568;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }

        .menu-item:hover {
            background: #fef3e2;
            color: #f09e2b;
        }

        .menu-item.active {
            background: #fef3e2;
            border-left-color: #f09e2b;
            color: #f09e2b;
            font-weight: 600;
        }

        .menu-item-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .menu-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 15px 0;
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .menu-item span {
                display: none;
            }

            .search-input {
                width: 200px;
            }

            .user-details {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo">🐾</div>
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
                <span class="menu-item-icon">📊</span>
                <span>Dashboard</span>
            </a>
            
            <a href="?seccion=pacientes" class="menu-item <?= $seccion === 'pacientes' ? 'active' : '' ?>">
                <span class="menu-item-icon">🐾</span>
                <span>Pacientes</span>
            </a>
            
            <div class="menu-divider"></div>
            
            <div class="menu-section-title">Administración</div>
            <a href="?seccion=veterinarios" class="menu-item <?= $seccion === 'veterinarios' ? 'active' : '' ?>">
                <span class="menu-item-icon">👨‍⚕️</span>
                <span>Veterinarios</span>
            </a>
            
            <?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])): ?>
                <a href="?seccion=registrar_vet" class="menu-item <?= $seccion === 'registrar_vet' ? 'active' : '' ?>">
                    <span class="menu-item-icon">➕</span>
                    <span>Nuevo Veterinario</span>
                </a>
            <?php endif; ?>

            <div class="menu-divider"></div>
            
            <div class="menu-section-title">Soporte</div>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">❓</span>
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