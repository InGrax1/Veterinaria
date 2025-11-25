<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Si viene del formulario de verificacion de usuario y correo
    if (isset($_POST['usuario']) && isset($_POST['correo']) && !isset($_POST['nueva_password'])) {
        $usuario = trim($_POST['usuario']);
        $correo = trim($_POST['correo']);
        
        $usuario_encontrado = false;
        $tipo_usuario = '';
        $nombre_usuario = '';
        $id_usuario = 0;
        
        // PRIMERO: Buscar en PROPIETARIOS
        $sql_propietario = "SELECT id_propietario, nombre FROM propietario WHERE usuario = ? AND correo = ?";
        $stmt_propietario = $conn->prepare($sql_propietario);
        $stmt_propietario->bind_param("ss", $usuario, $correo);
        $stmt_propietario->execute();
        $result_propietario = $stmt_propietario->get_result();
        
        if ($result_propietario->num_rows === 1) {
            $row = $result_propietario->fetch_assoc();
            $usuario_encontrado = true;
            $tipo_usuario = 'propietario';
            $nombre_usuario = $row['nombre'];
            $id_usuario = $row['id_propietario'];
        }
        $stmt_propietario->close();
        
        // SEGUNDO: Si no es propietario, buscar en VETERINARIOS
        if (!$usuario_encontrado) {
            $sql_veterinario = "SELECT id_veterinario, nombre FROM veterinario WHERE usuario = ? AND correo_electronico = ?";
            $stmt_veterinario = $conn->prepare($sql_veterinario);
            $stmt_veterinario->bind_param("ss", $usuario, $correo);
            $stmt_veterinario->execute();
            $result_veterinario = $stmt_veterinario->get_result();
            
            if ($result_veterinario->num_rows === 1) {
                $row = $result_veterinario->fetch_assoc();
                $usuario_encontrado = true;
                $tipo_usuario = 'veterinario';
                $nombre_usuario = $row['nombre'];
                $id_usuario = $row['id_veterinario'];
            }
            $stmt_veterinario->close();
        }
        
        if ($usuario_encontrado) {
            // Usuario y correo coinciden, guardar en sesion
            $_SESSION['reset_usuario'] = $usuario;
            $_SESSION['reset_nombre'] = $nombre_usuario;
            $_SESSION['reset_id'] = $id_usuario;
            $_SESSION['reset_tipo'] = $tipo_usuario; // IMPORTANTE: guardar el tipo
            
            // Mostrar formulario para nueva contraseña
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Nueva Contraseña - Clínica Patitas Felices</title>
                
                <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
                <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
                
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                        background-color: #f4f4f5;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 20px;
                    }
                    
                    .recovery-box {
                        max-width: 450px;
                        width: 100%;
                        background: white;
                        padding: 40px;
                        border-radius: 24px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                    }
                    
                    .logo {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 10px;
                        font-size: 20px;
                        font-weight: 700;
                        color: #f09e2b;
                        margin-bottom: 30px;
                    }
                    
                    .logo-icon {
                        font-size: 24px;
                    }
                    
                    .success-icon {
                        text-align: center;
                        font-size: 64px;
                        margin-bottom: 20px;
                        animation: scaleIn 0.5s ease;
                    }
                    
                    @keyframes scaleIn {
                        from { transform: scale(0); }
                        to { transform: scale(1); }
                    }
                    
                    h1 {
                        font-size: 28px;
                        font-weight: 800;
                        color: #1f2937;
                        margin: 0 0 8px 0;
                        text-align: center;
                    }
                    
                    .subtitle {
                        font-size: 15px;
                        color: #6b7280;
                        margin: 0 0 24px 0;
                        text-align: center;
                        line-height: 1.5;
                    }
                    
                    .alert {
                        padding: 12px 16px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                        font-size: 14px;
                    }
                    
                    .alert-success {
                        background-color: #d1fae5;
                        color: #065f46;
                        border: 1px solid #6ee7b7;
                    }
                    
                    .user-type-badge {
                        display: inline-block;
                        padding: 4px 12px;
                        border-radius: 12px;
                        font-size: 12px;
                        font-weight: 600;
                        margin-left: 8px;
                    }
                    
                    .badge-propietario {
                        background-color: #dbeafe;
                        color: #1e40af;
                    }
                    
                    .badge-veterinario {
                        background-color: #fef3e7;
                        color: #d97706;
                    }
                    
                    .form-group {
                        margin-bottom: 20px;
                    }
                    
                    label {
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 600;
                        color: #374151;
                        font-size: 14px;
                    }
                    
                    input[type="password"] {
                        width: 100%;
                        padding: 12px 14px;
                        border: 1px solid #d1d5db;
                        border-radius: 8px;
                        font-size: 14px;
                        line-height: 1.5;
                        font-family: inherit;
                        transition: all 0.2s ease;
                    }
                    
                    input[type="password"]:focus {
                        outline: none;
                        border-color: #f09e2b;
                        box-shadow: 0 0 0 3px rgba(240, 158, 43, 0.1);
                    }
                    
                    input::placeholder {
                        color: #9ca3af;
                    }
                    
                    .password-tips {
                        background-color: #f9fafb;
                        padding: 15px;
                        border-radius: 8px;
                        margin: 20px 0;
                        font-size: 13px;
                        color: #6b7280;
                    }
                    
                    .password-tips strong {
                        color: #374151;
                        display: block;
                        margin-bottom: 8px;
                    }
                    
                    .btn-login {
                        width: 100%;
                        padding: 14px;
                        background-color: #f09e2b;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        font-size: 16px;
                        font-weight: 700;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        margin-top: 10px;
                    }
                    
                    .btn-login:hover {
                        background-color: #e69121;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 12px rgba(240, 158, 43, 0.3);
                    }
                </style>
            </head>
            <body>

                <div class="recovery-box">
                    
                    <div class="logo">
                        <span class="logo-icon">🐾</span>
                        Clínica Patitas Felices
                    </div>

                    <div class="success-icon">✅</div>

                    <h1>¡Verificación Exitosa!</h1>
                    <p class="subtitle">
                        Hola <?php echo htmlspecialchars($nombre_usuario); ?>
                        <span class="user-type-badge badge-<?php echo $tipo_usuario; ?>">
                            <?php echo $tipo_usuario === 'propietario' ? '👤 Usuario' : '⚕️ Veterinario'; ?>
                        </span>
                        <br>Ahora puedes crear tu nueva contraseña.
                    </p>

                    <div class="alert alert-success">
                        <strong>✓ Identidad verificada</strong><br>
                        Tu identidad ha sido confirmada. Crea una contraseña segura.
                    </div>

                    <form method="POST" action="reset_simple.php" id="resetForm">
                        
                        <div class="form-group">
                            <label for="nueva_password">Nueva Contraseña</label>
                            <input type="password" id="nueva_password" name="nueva_password" placeholder="Mínimo 6 caracteres" required minlength="6">
                        </div>

                        <div class="form-group">
                            <label for="confirmar_password">Confirmar Nueva Contraseña</label>
                            <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Repite tu contraseña" required>
                        </div>

                        <div class="password-tips">
                            <strong>💡 Consejos para una contraseña segura:</strong>
                            • Usa al menos 6 caracteres<br>
                            • Combina letras y números<br>
                            • Evita usar información personal obvia
                        </div>

                        <button type="submit" class="btn-login">Cambiar Contraseña</button>
                    </form>

                </div>

                <script>
                    // Validar que las contraseñas coincidan
                    document.getElementById('resetForm').addEventListener('submit', function(e) {
                        const password = document.getElementById('nueva_password').value;
                        const confirm = document.getElementById('confirmar_password').value;
                        
                        if (password !== confirm) {
                            e.preventDefault();
                            alert('Las contraseñas no coinciden. Por favor, verifica.');
                            document.getElementById('confirmar_password').focus();
                            return false;
                        }
                        
                        if (password.length < 6) {
                            e.preventDefault();
                            alert('La contraseña debe tener al menos 6 caracteres.');
                            document.getElementById('nueva_password').focus();
                            return false;
                        }
                    });
                    
                    // Indicador visual de coincidencia
                    document.getElementById('confirmar_password').addEventListener('input', function() {
                        const password = document.getElementById('nueva_password').value;
                        if (this.value && this.value === password) {
                            this.style.borderColor = '#10b981';
                        } else if (this.value) {
                            this.style.borderColor = '#ef4444';
                        } else {
                            this.style.borderColor = '#d1d5db';
                        }
                    });
                </script>

            </body>
            </html>
            <?php
            exit();
            
        } else {
            echo "<script>alert('Usuario o correo incorrectos. Verifica tus datos.'); window.location.href='forgot_password_simple.php';</script>";
            exit();
        }
    }
    
    // Si viene del formulario de cambio de contraseña
    if (isset($_POST['nueva_password']) && isset($_POST['confirmar_password'])) {
        
        // Verificar que haya sesion activa
        if (!isset($_SESSION['reset_usuario']) || !isset($_SESSION['reset_tipo'])) {
            echo "<script>alert('Sesión expirada. Intenta nuevamente.'); window.location.href='forgot_password_simple.php';</script>";
            exit();
        }
        
        $usuario = $_SESSION['reset_usuario'];
        $tipo_usuario = $_SESSION['reset_tipo'];
        $nueva_password = $_POST['nueva_password'];
        $confirmar_password = $_POST['confirmar_password'];
        
        // Validar que las contraseñas coincidan
        if ($nueva_password !== $confirmar_password) {
            echo "<script>alert('Las contraseñas no coinciden.'); history.back();</script>";
            exit();
        }
        
        // Validar longitud minima
        if (strlen($nueva_password) < 6) {
            echo "<script>alert('La contraseña debe tener al menos 6 caracteres.'); history.back();</script>";
            exit();
        }
        
        // Actualizar la contraseña según el tipo de usuario
        if ($tipo_usuario === 'propietario') {
            // Actualizar en tabla PROPIETARIO
            $sql_update = "UPDATE propietario SET password = ? WHERE usuario = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $nueva_password, $usuario);
        } else {
            // Actualizar en tabla VETERINARIO
            $sql_update = "UPDATE veterinario SET password = ? WHERE usuario = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $nueva_password, $usuario);
        }
        
        if ($stmt_update->execute()) {
            // Limpiar sesion
            unset($_SESSION['reset_usuario']);
            unset($_SESSION['reset_nombre']);
            unset($_SESSION['reset_id']);
            unset($_SESSION['reset_tipo']);
            
            // Mostrar pagina de exito
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Contraseña Actualizada - Clínica Patitas Felices</title>
                
                <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
                <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
                
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                        background-color: #f4f4f5;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 20px;
                    }
                    
                    .recovery-box {
                        max-width: 450px;
                        width: 100%;
                        background: white;
                        padding: 40px;
                        border-radius: 24px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                    }
                    
                    .logo {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 10px;
                        font-size: 20px;
                        font-weight: 700;
                        color: #f09e2b;
                        margin-bottom: 30px;
                    }
                    
                    .logo-icon {
                        font-size: 24px;
                    }
                    
                    .success-icon {
                        text-align: center;
                        font-size: 80px;
                        margin-bottom: 20px;
                        animation: celebrate 0.6s ease;
                    }
                    
                    @keyframes celebrate {
                        0%, 100% { transform: scale(1) rotate(0deg); }
                        25% { transform: scale(1.1) rotate(-10deg); }
                        75% { transform: scale(1.1) rotate(10deg); }
                    }
                    
                    h1 {
                        font-size: 28px;
                        font-weight: 800;
                        color: #1f2937;
                        margin: 0 0 8px 0;
                        text-align: center;
                    }
                    
                    .subtitle {
                        font-size: 15px;
                        color: #6b7280;
                        margin: 0 0 24px 0;
                        text-align: center;
                        line-height: 1.5;
                    }
                    
                    .alert {
                        padding: 12px 16px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                        font-size: 14px;
                    }
                    
                    .alert-success {
                        background-color: #d1fae5;
                        color: #065f46;
                        border: 1px solid #6ee7b7;
                    }
                    
                    .btn-login {
                        width: 100%;
                        padding: 14px;
                        background-color: #f09e2b;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        font-size: 16px;
                        font-weight: 700;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        text-decoration: none;
                        display: block;
                        text-align: center;
                    }
                    
                    .btn-login:hover {
                        background-color: #e69121;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 12px rgba(240, 158, 43, 0.3);
                    }
                    
                    .forgot-password {
                        display: block;
                        text-align: center;
                        margin-top: 20px;
                        color: #f09e2b;
                        text-decoration: none;
                        font-size: 14px;
                        font-weight: 600;
                    }
                </style>
            </head>
            <body>

                <div class="recovery-box">
                    
                    <div class="logo">
                        <span class="logo-icon">🐾</span>
                        Clínica Patitas Felices
                    </div>

                    <div class="success-icon">🎉</div>

                    <h1>¡Contraseña Actualizada!</h1>
                    <p class="subtitle">
                        Tu contraseña ha sido cambiada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.
                    </p>

                    <div class="alert alert-success">
                        <strong>✓ Cambio exitoso</strong><br>
                        Tu cuenta está segura. Ya puedes iniciar sesión.
                    </div>

                    <a href="index.php" class="btn-login">Iniciar Sesión</a>

                    <a href="../inicio.php" class="forgot-password">
                        ← Volver al inicio
                    </a>
                </div>

                <script>
                    // Redirigir automaticamente despues de 5 segundos
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 5000);
                </script>

            </body>
            </html>
            <?php
            exit();
            
        } else {
            echo "<script>alert('Error al actualizar la contraseña. Intenta nuevamente.'); history.back();</script>";
            exit();
        }
    }
    
    $conn->close();
}
?>