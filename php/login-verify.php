<?php
session_start();

// Verificar si la sesión está activa (si el usuario está logueado)
if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {

    header("Location: login.php");
    exit;
}
?>