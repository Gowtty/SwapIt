<?php

$servername = "localhost"; 
$username = "root";
$password = "jeanmay";
$dbname = "SwapIt";

// Connect

$conn = new mysqli($servername, $username, $password, $dbname);

// Verify connection

if ($conn->connect_error){
    die("Conexión fallida: " . $conn->connect_error);
} else{
    echo "Conexion exitosa.";
}

?>