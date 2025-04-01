<?php
include 'connectDB.php';

function hasActiveOffers($conn, $item_id) {
    // Check for any pending or accepted offers where this item is either the offered item or the target item
    $query = "SELECT COUNT(*) as count FROM offers 
              WHERE (item_id = ? OR offered_item_id = ?) 
              AND status IN ('Pendiente', 'Aceptada')";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $item_id, $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return $row['count'] > 0;
}

function getItemStatus($conn, $item_id) {
    $query = "SELECT status FROM item WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['status'] ?? null;
}
?> 