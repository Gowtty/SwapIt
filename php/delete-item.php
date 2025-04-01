<?php
session_start();
include 'connectDB.php';
include 'checkSession.php';
include 'check-item-offers.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para eliminar un artículo.']);
    exit;
}

$item_id = $_POST['item_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$item_id) {
    echo json_encode(['success' => false, 'message' => 'ID del artículo no proporcionado.']);
    exit;
}

// Verify that the item belongs to the user
$query = "SELECT id FROM item WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar este artículo.']);
    exit;
}

// Check if the item has any active offers
if (hasActiveOffers($conn, $item_id)) {
    echo json_encode(['success' => false, 'message' => 'No puedes eliminar este artículo porque tiene ofertas activas o pendientes.']);
    exit;
}

// Check if the item is reserved
$item_status = getItemStatus($conn, $item_id);
if ($item_status === 'Reservado') {
    echo json_encode(['success' => false, 'message' => 'No puedes eliminar este artículo porque está reservado para un intercambio.']);
    exit;
}

// Delete the item
$query = "DELETE FROM item WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Artículo eliminado exitosamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el artículo.']);
}
?> 