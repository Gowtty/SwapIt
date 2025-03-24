<?php
session_start();
include '../php/connectDB.php';

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prevenir inyecciones SQL usando prepared statements
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" significa string
    $stmt->execute();
    $result = $stmt->get_result();

    // Si se encuentra el email en la BD entonces
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['usuario_logueado'] = true;
            echo json_encode(["tipo" => "success", "texto" => "Inicio de sesión exitoso"]);
        } else {
            echo json_encode(["tipo" => "error", "texto" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["tipo" => "error", "texto" => "El correo ingresado no está registrado"]);
    }
}
    $stmt->close();
    $conn->close();
    exit();
?>