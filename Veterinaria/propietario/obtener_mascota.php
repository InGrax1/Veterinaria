<?php
include '../includes/scriptdb.php';

if (isset($_GET['id_mascota'])) {
    $id_mascota = $_GET['id_mascota'];

    // Obtener los datos de la mascota seleccionada
    $sql = "SELECT nombre, edad, especie, raza, sexo FROM mascota WHERE id_mascota = $id_mascota";
    $result = mysqli_query($conn, $sql);
    $mascota = mysqli_fetch_assoc($result);

    // Mostrar los datos de la mascota
    if ($mascota) {
        echo "
            <div class='editable'>
                <label>Nombre:</label>
                <input type='text' name='mascota_nombre' value='" . $mascota['nombre'] . "'>
            </div>
            <div class='editable'>
                <label>Edad:</label>
                <input type='number' name='mascota_edad' value='" . $mascota['edad'] . "'>
            </div>
            <div class='editable'>
                <label>Especie:</label>
                <input type='text' name='mascota_especie' value='" . $mascota['especie'] . "'>
            </div>
            <div class='editable'>
                <label>Raza:</label>
                <input type='text' name='mascota_raza' value='" . $mascota['raza'] . "'>
            </div>
            <div class='editable'>
                <label>Sexo:</label>
                <input type='text' name='mascota_sexo' value='" . $mascota['sexo'] . "'>
            </div>
        ";
    } else {
        echo "No se encontraron detalles de la mascota.";
    }
}
?>
