<?php
session_start();
include '../includes/scriptdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Si viene del formulario de verificacion de usuario y correo
    if (isset($_POST['usuario']) && isset($_POST['correo']) && !isset($_POST['nueva_password'])) {
        $usuario = trim($_POST['usuario']);
        $correo = trim($_POST['correo']);
        
        // Verificar que el usuario y correo coincidan en la base de datos
        $sql = "SELECT id_propietario, nombre FROM propietario WHERE usuario = ? AND correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            // Usuario y correo coinciden, guardar en sesion
            $row = $result->fetch_assoc();
            $_SESSION['reset_usuario'] = $usuario;
            $_SESSION['reset_nombre'] = $row['nombre'];
            $_SESSION['reset_id'] = $row['id_propietario'];
            
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
                
                <link rel="stylesheet" href="../css/stilogin.css">
                
                <style>
                    .recovery-box {
                        max-width: 450px;
                        margin: 40px auto;
                        background: white;
                        padding: 40px;
                        border-radius: 24px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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
                </style>
            </head>
            <body>

                <div class="recovery-box">
                    
                    <div class="logo" style="justify-content: center; margin-bottom: 30px;">
                        <span class="logo-icon">🐾</span>
                        Clínica Patitas Felices
                    </div>

                    <div class="success-icon">✅</div>

                    <h1 style="text-align: center;">¡Verificación Exitosa!</h1>
                    <p class="subtitle" style="text-align: center;">
                        Hola <?php echo htmlspecialchars($row['nombre']); ?>, ahora puedes crear tu nueva contraseña.
                    </p>

                    <div class="alert alert-success">
                        <strong>✓ Usuario verificado</strong><br>
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
        if (!isset($_SESSION['reset_usuario'])) {
            echo "<script>alert('Sesión expirada. Intenta nuevamente.'); window.location.href='forgot_password_simple.php';</script>";
            exit();
        }
        
        $usuario = $_SESSION['reset_usuario'];
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
        
        // Actualizar la contraseña en la base de datos
        $sql_update = "UPDATE propietario SET password = ? WHERE usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $nueva_password, $usuario);
        
        if ($stmt_update->execute()) {
            // Limpiar sesion
            unset($_SESSION['reset_usuario']);
            unset($_SESSION['reset_nombre']);
            unset($_SESSION['reset_id']);
            
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
                
                <link rel="stylesheet" href="../css/stilogin.css">
                
                <style>
                    .recovery-box {
                        max-width: 450px;
                        margin: 40px auto;
                        background: white;
                        padding: 40px;
                        border-radius: 24px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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
                </style>
            </head>
            <body>

                <div class="recovery-box">
                    
                    <div class="logo" style="justify-content: center; margin-bottom: 30px;">
                        <span class="logo-icon">🐾</span>
                        Clínica Patitas Felices
                    </div>

                    <div class="success-icon">🎉</div>

                    <h1 style="text-align: center;">¡Contraseña Actualizada!</h1>
                    <p class="subtitle" style="text-align: center;">
                        Tu contraseña ha sido cambiada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.
                    </p>

                    <div class="alert alert-success">
                        <strong>✓ Cambio exitoso</strong><br>
                        Tu cuenta está segura. Ya puedes iniciar sesión.
                    </div>

                    <a href="index.php">
                        <button class="btn-login">Iniciar Sesión</button>
                    </a>

                    <a href="../inicio.php" class="forgot-password" style="display: block; text-align: center; margin-top: 20px;">
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