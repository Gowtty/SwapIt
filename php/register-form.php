<?php
session_start();
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
        $_SESSION['notification'] = [
            'message' => 'Todos los campos son obligatorios.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/register.php';</script>";
        exit;
    }

    // Verificar si el email ya existe
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['notification'] = [
            'message' => 'Este correo electrónico ya está registrado.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/register.php';</script>";
        exit;
    }

    // Verificar si el nombre de usuario ya existe
    $check_username = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_username);
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['notification'] = [
            'message' => 'Este nombre de usuario ya está en uso.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/register.php';</script>";
        exit;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //Consulta SQL
    $sql = "INSERT INTO users (username, email, password_hash, phone, state, city) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $name, $email, $hashed_password, $phone, $state, $city);

    //Ejecutar SQL
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['notification'] = [
            'message' => '¡Registro exitoso! Por favor, inicia sesión.',
            'type' => 'success'
        ];
        echo "<script>window.location.href = '../pages/login.php';</script>";
        exit;
    } else {
        $_SESSION['notification'] = [
            'message' => 'Error al registrar el usuario. Por favor, intenta nuevamente.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/register.php';</script>";
        exit;
    }
}

$conn->close();
?>