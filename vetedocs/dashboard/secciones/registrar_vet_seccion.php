<?php
$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $especialidad = $_POST['especialidad'];

    // Verificar si el correo ya existe
    $verificarCorreo = mysqli_query($conn, "SELECT * FROM veterinario WHERE correo_electronico = '$correo'");
    // Verificar si el teléfono ya existe
    $verificarTelefono = mysqli_query($conn, "SELECT * FROM veterinario WHERE telefono = '$telefono'");

    if (mysqli_num_rows($verificarCorreo) > 0) {
        $mensaje = "Ya existe un veterinario registrado con ese correo electrónico.";
        $tipo_mensaje = "error";
    } elseif (mysqli_num_rows($verificarTelefono) > 0) {
        $mensaje = "Ya existe un veterinario registrado con ese número de teléfono.";
        $tipo_mensaje = "error";
    } else {
        $sql = "INSERT INTO veterinario (nombre, telefono, correo_electronico, especialidad)
                VALUES ('$nombre', '$telefono', '$correo', '$especialidad')";

        if (mysqli_query($conn, $sql)) {
            $mensaje = "Veterinario registrado correctamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar: " . mysqli_error($conn);
            $tipo_mensaje = "error";
        }
    }
}
?>

<style>
    .register-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .register-header {
        margin-bottom: 30px;
    }

    .register-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 10px;
    }

    .register-header p {
        color: #718096;
        font-size: 16px;
    }

    .register-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 2px solid;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: #f09e2b;
        box-shadow: 0 0 0 3px rgba(240, 158, 43, 0.1);
    }

    .form-input::placeholder {
        color: #a0aec0;
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #f09e2b, #fbbf24);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(240, 158, 43, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .form-icon {
        position: relative;
    }

    .input-with-icon {
        padding-left: 45px;
    }

    .icon-prefix {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: #a0aec0;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .register-card {
            padding: 25px;
        }
    }
</style>

<div class="register-container">
    <div class="register-header">
        <h2>➕ Registrar Nuevo Veterinario</h2>
        <p>Complete el formulario para agregar un veterinario al sistema</p>
    </div>

    <div class="register-card">
        <?php if ($mensaje): ?>
            <div class="alert <?= $tipo_mensaje === 'success' ? 'alert-success' : 'alert-error' ?>">
                <?= $tipo_mensaje === 'success' ? '✅' : '❌' ?>
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nombre completo *</label>
                    <div class="form-icon">
                        <i class="fas fa-user" style="color: #f09e2b;"></i>
                        <input type="text" 
                               name="nombre" 
                               class="form-input input-with-icon" 
                               placeholder="Ej: Dr. Juan Pérez"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Especialidad *</label>
                    <div class="form-icon">
                        <i class="fas fa-pills" style="color: #f09e2b;"></i>
                        <input type="text" 
                               name="especialidad" 
                               class="form-input input-with-icon" 
                               placeholder="Ej: Cirugía, Medicina General"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Correo electrónico *</label>
                    <div class="form-icon">
                        <span class="icon-prefix">📧</span>
                        <input type="email" 
                               name="correo" 
                               class="form-input input-with-icon" 
                               placeholder="veterinario@ejemplo.com"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Teléfono *</label>
                    <div class="form-icon">
                        <span class="icon-prefix">📱</span>
                        <input type="tel" 
                               name="telefono" 
                               class="form-input input-with-icon" 
                               placeholder="5512345678"
                               required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                ✨ Registrar Veterinario
            </button>
        </form>
    </div>
</div>