<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-M">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Clínica VetCare</title>
    
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    
    <link rel="stylesheet" href="../css/stilogin.css">
</head>
<body>

    <div class="login-container">
        
        <div class="login-image-panel">
            </div>

        <div class="login-form-panel">
            
            <div class="logo">
                <span class="logo-icon">🐾</span>
                Clínica Patitas Felices
            </div>

            <h1>¡Hola de nuevo!</h1>
            <p class="subtitle">Bienvenido a la clínica veterinaria Patitas Felices</p>

            <form method="POST" action="login.php">
                
                <div class="form-group">
                    <label for="usuario">Nombre de Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Escribe tu usuario" required>
                </div>

                <div class="form-group">
                    <div class="form-row-label">
                        <label for="password">Contraseña</label>
                        <a href="forgot_password_simple.php" class="forgot-password">¿Olvidé mi contraseña?</a>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>
                        <span class="eye-icon" onclick="togglePassword()">👁</span>
                    </div>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>
            
            <a href="../inicio.php" class="forgot-password" style="display: block; text-align: center; margin-top: 15px; margin-bottom: 10px;">
                ← Volver al Inicio
            </a>

            <p class="register-link">
            ¿No tienes una cuenta? <a href="../registro/registro.php">Regístrate</a>
        </p>
        </div>

    </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = ' X'; // Ojo cerrado
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁'; // Ojo abierto
            }
        }
    </script>
</body>
</html>
</body>
</html>