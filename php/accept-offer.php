<?php
session_start();
include '../php/connectDB.php';

if (!isset($_SESSION['user_id'])) {
    echo "Debes iniciar sesión para aceptar una oferta.";
    exit;
}

$user_id = $_SESSION['user_id'];
$offer_id = $_GET['id'] ?? null;

if (!$offer_id) {
    echo "ID de oferta no proporcionado.";
    exit;
}

// Verificar que la oferta pertenece al usuario propietario
$query = "SELECT * FROM offers WHERE id = ? AND item_id IN (SELECT id FROM item WHERE user_id = ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $offer_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$offer = mysqli_fetch_assoc($result);

if (!$offer) {
    echo "Oferta no válida.";
    exit;
}

// Actualizar el estado de la oferta a "Aceptada"
$query = "UPDATE offers SET status = 'Aceptada' WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $offer_id);
mysqli_stmt_execute($stmt);

// Actualizar el estado del ítem a "Reservado"
$query = "UPDATE item SET status = 'Reservado' WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $offer['item_id']);
mysqli_stmt_execute($stmt);

header("Location: ../pages/my-offers.php");
exit;
?>