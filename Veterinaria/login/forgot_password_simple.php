<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Clínica Patitas Felices</title>
    
    <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
    
    <style>
        /* Reset y estilos base */
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
        
        .recovery-icon {
            text-align: center;
            font-size: 64px;
            margin-bottom: 20px;
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
        
        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .alert strong {
            font-weight: 600;
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
        
        /* Estilos para TODOS los inputs */
        input[type="text"],
        input[type="email"],
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
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #f09e2b;
            box-shadow: 0 0 0 3px rgba(240, 158, 43, 0.1);
        }
        
        input::placeholder {
            color: #9ca3af;
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
        
        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #f09e2b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        
        .forgot-password:hover {
            color: #e69121;
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 0;
            }
            
            .recovery-box {
                border-radius: 0;
                box-shadow: none;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div class="recovery-box">
        
        <div class="logo">
            <span class="logo-icon">🐾</span>
            Clínica Patitas Felices
        </div>

        <div class="recovery-icon">🔑</div>

        <h1>¿Olvidaste tu contraseña?</h1>
        <p class="subtitle">Ingresa tu usuario y correo. Si coinciden, podrás crear una nueva contraseña.</p>

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

        <a href="index.php" class="forgot-password">
            ← Volver al inicio de sesión
        </a>
    </div>

</body>
</html>