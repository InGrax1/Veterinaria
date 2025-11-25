<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Clínica Patitas Felices</title>
    
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
        
        .recovery-icon {
            text-align: center;
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
    </style>
</head>
<body>

    <div class="recovery-box">
        
        <div class="logo" style="justify-content: center; margin-bottom: 30px;">
            <span class="logo-icon">🐾</span>
            Clínica Patitas Felices
        </div>

        <div class="recovery-icon">🔑</div>

        <h1 style="text-align: center;">¿Olvidaste tu contraseña?</h1>
        <p class="subtitle" style="text-align: center;">Ingresa tu usuario y correo. Si coinciden, podrás crear una nueva contraseña.</p>

        <div class="alert alert-info">
            <strong>ℹ️ Nota:</strong> Solo podrás cambiar tu contraseña si el usuario y correo coinciden con los registrados.
        </div>

        <form method="POST" action="reset_simple.php">
            
            <div class="form-group">
                <label for="usuario">Nombre de Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="tu_usuario" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="tu@correo.com" required>
            </div>

            <button type="submit" class="btn-login">Continuar</button>
        </form>

        <a href="index.php" class="forgot-password" style="display: block; text-align: center; margin-top: 20px;">
            ← Volver al inicio de sesión
        </a>
    </div>

</body>
</html>