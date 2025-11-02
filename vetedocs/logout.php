<?php
session_start();
session_destroy();
header("Location: ../Veterinaria/inicio.php");
exit;
