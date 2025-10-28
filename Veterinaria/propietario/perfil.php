<?php
session_start();
include '../includes/scriptdb.php';

if (!isset($_SESSION['id_propietario'])) {
    echo "<script>alert('Debes iniciar sesión primero.'); window.location.href='../login/index.php';</script>";
    exit;
}

$id_propietario = $_SESSION['id_propietario'];

// Obtener datos del propietario
$sql_propietario = "SELECT * FROM propietario WHERE id_propietario = $id_propietario LIMIT 1";
$result_propietario = mysqli_query($conn, $sql_propietario);
$cliente = mysqli_fetch_assoc($result_propietario);

// Obtener todas las mascotas del propietario
$sql_mascotas = "SELECT * FROM mascota WHERE id_propietario = $id_propietario";
$result_mascotas = mysqli_query($conn, $sql_mascotas);

$mascotas = [];
while ($row = mysqli_fetch_assoc($result_mascotas)) {
    $mascotas[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Perfil del Cliente</title>
  <link rel="stylesheet" href="../css/perfil.css">
  
  <link rel="apple-touch-icon" type="image/jpg" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
  <link rel="shortcut icon" type="image/x-icon" href="https://i.postimg.cc/q7YcnW3s/logo.png"/>
</head>
<body>

<header class="header">
  <img src="https://i.postimg.cc/q7YcnW3s/logo.png" alt="Logo Patitas Felices" class="logo">
  <h1>Clínica Veterinaria Patitas Felices</h1>
  <nav>
    <ul class="nav-menu">
      <li><a href="../inicio.php">Inicio</a></li>
      <li><a href="#">Perfil</a></li>
      <li class="dropdown">
        <button class="dropbtn">Más Opciones</button>
        <div class="dropdown-content">
          <a href="../citas/regicita.php">Registrar Cita</a>
          <a href="../citas/ver_citas.php">Ver Citas</a>
          <a href="../historial/historial.php">Historial Clínico</a>
          <a href="../contacto.php">Contacto</a>
          <a href="../includes/logout.php">Cerrar sesión</a>
        </div>
      </li>
    </ul>
  </nav>
</header>

<form action="actualizar_perfil.php" method="POST" id="perfilForm">
  <h2>Información del Cliente</h2>
  <?php
  function campoEditable($label, $name, $valor, $id = "") {
    echo "<div class='editable'>";
    echo "<label>$label:</label> ";
    echo "<span id='label_{$name}{$id}'>$valor</span>";
    echo "<input class='hidden' type='text' name='{$name}{$id}' id='input_{$name}{$id}' value='$valor'>";
    echo "<span class='edit-icon' onclick='editarCampo(\"{$name}{$id}\")'>✏️</span>";
    echo "</div><br>";
  }

  campoEditable("Nombre completo", "nombre", $cliente['nombre']);
  campoEditable("Correo electrónico", "correo", $cliente['correo']);
  campoEditable("Teléfono", "telefono", $cliente['telefono']);
  campoEditable("Dirección", "direccion", $cliente['direccion']);
  campoEditable("Usuario", "usuario", $cliente['usuario']);
  echo "<div class='editable'>";
  echo "<label>Contraseña:</label>";
  echo "<span id='label_password'>********</span>";
  echo "<input class='hidden' type='password' name='password' id='input_password'>";
  echo "<span class='edit-icon' onclick='editarCampo(\"password\")'>✏️</span>";
  echo "</div><br>";
  ?>

  <h2>🔹 Mascotas Registradas</h2>
  <br>
  <?php foreach ($mascotas as $index => $mascota): ?>
    <fieldset style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
      <legend>Mascota <?= $index + 1 ?></legend>
      <input type="hidden" name="mascotas[<?= $index ?>][id_mascota]" value="<?= $mascota['id_mascota'] ?>">
      <?php
        campoEditable("Nombre", "mascotas[$index][nombre]", $mascota['nombre'], "_$index");
        campoEditable("Edad", "mascotas[$index][edad]", $mascota['edad'], "_$index");
        campoEditable("Especie", "mascotas[$index][especie]", $mascota['especie'], "_$index");
        campoEditable("Raza", "mascotas[$index][raza]", $mascota['raza'], "_$index");
        campoEditable("Sexo", "mascotas[$index][sexo]", $mascota['sexo'], "_$index");
      ?>
    </fieldset>
  <?php endforeach; ?>

  <input type="submit" value="Guardar cambios" id="guardarBtn" class="guardar-btn hidden">
  <button type="button" id="cancelarBtn" class="cancelar-btn hidden" onclick="cancelarEdicion()">Cancelar edición</button>
</form>

<script>
function editarCampo(campo) {
  document.getElementById("label_" + campo).classList.add("hidden");
  document.getElementById("input_" + campo).classList.remove("hidden");
  document.getElementById("guardarBtn").classList.remove("hidden");
  document.getElementById("cancelarBtn").classList.remove("hidden");
}

function cancelarEdicion() {
  const inputs = document.querySelectorAll("input[type='text'], input[type='password']");
  inputs.forEach(input => {
    const id = input.id.replace("input_", "label_");
    const label = document.getElementById(id);
    if (label) {
      input.value = label.innerText;
      input.classList.add("hidden");
      label.classList.remove("hidden");
    }
  });

  document.getElementById("guardarBtn").classList.add("hidden");
  document.getElementById("cancelarBtn").classList.add("hidden");
}
</script>

<footer class="footer">
  <p>Clínica Veterinaria Patitas Felices © 2025 | Teléfono: (123) 456-7890 | Email: contacto@patitasfelices.com</p>
</footer>

</body>
</html>
