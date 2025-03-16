<?php
session_start();
include 'connectDB.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prevenir inyecciones SQL usando prepared statements
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" significa string
    $stmt->execute();
    $result = $stmt->get_result();

    // Si se encuentra el email en la BD entonces
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();

        //Verificar si la contraseña coincide con el hash de la BD
        if(password_verify($password, $row['password_hash'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION["mensaje"] = ["tipo" => "success", "texto" => "Inicio de sesión exitoso"];
            header("Location: ../pages/profile.php");
            exit();
        }else{
            $_SESSION["mensaje"] = ["tipo" => "error", "texto" => "Contraseña incorrecta"];
            header("Location: ../pages/login.php");
            exit();
        }

    }else {
        $_SESSION["mensaje"] = ["tipo" => "error", "texto" => "El correo ingresado no se encuentra registrado"];
        header("Location: ../pages/login.php");
        exit();
    }
}
    $stmt->close();
    $conn->close();
?>