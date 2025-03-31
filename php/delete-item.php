<?php
session_start();
include 'connectDB.php';
include 'checkSession.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if (!$item_id) {
        $_SESSION['notification'] = [
            'message' => 'Error: ID del artículo no proporcionado.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/index.php';</script>";
        exit;
    }

    // Verificar que el artículo pertenece al usuario
    $check_query = "SELECT id FROM item WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $_SESSION['notification'] = [
            'message' => 'No tienes permiso para eliminar este artículo.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/my-items.php';</script>";
        exit;
    }

    // Eliminar el artículo
    $query = "DELETE FROM item WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['notification'] = [
            'message' => '¡Artículo eliminado exitosamente!',
            'type' => 'success'
        ];
        echo "<script>window.location.href = '../pages/my-items.php';</script>";
        exit;
    } else {
        $_SESSION['notification'] = [
            'message' => 'Error al eliminar el artículo. Por favor, intenta nuevamente.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/my-items.php';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href = '../pages/index.php';</script>";
    exit;
}
?> 