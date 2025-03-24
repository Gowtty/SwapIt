<?php
if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true) {
    include('../php/header-session.php');
} else {
    include('../php/header.php');
}
?>