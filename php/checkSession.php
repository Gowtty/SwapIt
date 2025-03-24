<?php
if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true) {
    // Si el usuario está logueado, muestra el header con opciones de usuario
    include('../php/header-session.php');
} else {
    // Si el usuario no está logueado, muestra el header normal
    include('../php/header.php');
}
?>