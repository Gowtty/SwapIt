<?php
// Verificar si la sesión está activa (si el usuario está logueado)
if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    header("Location: ../pages/login.php");
    exit;
}
?>