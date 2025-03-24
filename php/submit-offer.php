<?php
session_start();
include '../php/connectDB.php';

$user_id = $_SESSION['user_id'];
$item_id = $_POST['item_id'] ?? null;
$offered_item_id = $_POST['offered_item_id'] ?? null;

if (!$item_id || !$offered_item_id) {
    echo "Faltan datos para hacer la oferta.";
    exit;
}

// Verificar que el artículo que se está ofreciendo existe
$query = "SELECT * FROM item WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $offered_item_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$offered_item = mysqli_fetch_assoc($result);

if (!$offered_item) {
    echo "No puedes ofrecer este artículo.";
    exit;
}

// Insertar la oferta en la base de datos
$query = "INSERT INTO offers (offered_by, item_id, offered_item_id, status) VALUES (?, ?, ?, 'Pendiente')";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'iii', $user_id, $item_id, $offered_item_id);
mysqli_stmt_execute($stmt);

// Redirigir al usuario a una página de confirmación o a la página del artículo
header("Location: ../pages/item.php?id=" . $item_id);
exit;
?>