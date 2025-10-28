<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'veterinaria';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    $conn->set_charset('utf8mb4');
    date_default_timezone_set("America/Mexico_City");
}
?>
