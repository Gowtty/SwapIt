<?php

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "SwapIt";

// Connect

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4"); // Charset para acentos y caracteres especiales

// Verify connection

if ($conn->connect_error){
    die("Conexión fallida: " . $conn->connect_error);
}

?>