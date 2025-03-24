<?php
session_start();
include '../php/connectDB.php';
include '../php/login-verify.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['user_id'];
    $nuevo_username = trim($_POST['username']);
    $nuevo_email = trim($_POST['email']);
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
        die("Correo no válido.");
    }

    if (!empty($nueva_contrasena) && $nueva_contrasena !== $confirmar_contrasena) {
        die("Las contraseñas no coinciden.");
    }

    // Actualizar usuario sin contraseña
    $query = "UPDATE users SET username = ?, email = ?" .
             (!empty($nueva_contrasena) ? ", password_hash = ?" : "") .
             " WHERE id = ?";

    if (!empty($nueva_contrasena)) {
        $password_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $nuevo_username, $nuevo_email, $password_hash, $usuario_id);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nuevo_username, $nuevo_email, $usuario_id);
    }

    if ($stmt->execute()) {
        $_SESSION['nombre_usuario'] = $nuevo_username;
        header("Location: ../pages/user-config.php?status=ok");
        exit();
    } else {
        echo "Error al actualizar la información.";
    }

    $stmt->close();
    $conn->close();
}
?>