<?php
include 'connectDB.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Obtener datos del formulario
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password_hash'];
    $phone = $_POST['phone'];
    $state = $_POST['state'];
    $city = $_POST['city'];

    //Validar datos del form
    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($state) || empty($city)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //Consulta SQL
        $sql = "INSERT INTO users (username, email, password_hash, phone, state, city) VALUES ('$name', '$email', '$hashed_password', '$phone', '$state', '$city')";

        //Ejecutar SQL
        if ($conn->query($sql) == TRUE) {
            echo "Nuevo registro creado exitosamente";
            header("Location: ../pages/login.html");
            exit();
        }else{
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>