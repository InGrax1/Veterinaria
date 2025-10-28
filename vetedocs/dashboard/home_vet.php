<?php
session_start();

// Verificar si está logueado
if (!isset($_SESSION['veterinario_id'])) {
    header("Location: ../auth/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Veterinario</title>
    <link rel="stylesheet" href="../css/estilo_vet.css">

  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>
    <header class="header">
        <img class="logo" src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Veterinaria">
        <ul class="nav-menu">
            <li><a href="#">Inicio</a></li>
            
            <li><a href="../logout.php">Cerrar sesión</a></li>
        </ul>
    </header>

    <div class="dashboard-container">
        <h1>Bienvenid@, <?php echo $_SESSION['veterinario_nombre']; ?></h1>
        
        <div class="dashboard-options">
            <a href="pacientes_hoy.php" class="dashboard-button">Ver pacientes del día</a>
            <a href="agendar_siguiente.php" class="dashboard-button">Agendar próxima cita</a>
            <a href="../veterinarios/veterinarios.php" class="dashboard-button">Ver veterinarios</a>

            <?php if (in_array($_SESSION['veterinario_id'], [1, 2, 3])): ?>
                <a href="../veterinarios/registrar_veterinario.php" class="dashboard-button">Registrar Veterinario</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
