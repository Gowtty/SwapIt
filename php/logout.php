<?php
// Iniciar la sesión
session_start();

// Destruir todas las variables de la sesión
session_unset();

// Destruir la sesión
session_destroy();

header("Location: ../pages/index.php");
exit;
?>