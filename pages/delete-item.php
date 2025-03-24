<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/login-verify.php';

$user_id = $_SESSION['user_id'];

// Verificar que el ID del ítem sea proporcionado
$item_id = $_GET['id'] ?? null;

if (!$item_id) {
    echo "ID de ítem no proporcionado.";
    exit;
}

// Verificar si el ítem existe y si el usuario es el propietario
$query = "SELECT * FROM item WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo "Ítem no encontrado o no tienes permiso para eliminarlo.";
    exit;
}

// Eliminar las imágenes del servidor (opcional)
$image_paths = json_decode($item['images'], true) ?: [];
$target_dir = "../src/uploads/";

foreach ($image_paths as $image) {
    $image_path = $target_dir . basename($image);
    if (file_exists($image_path)) {
        if (unlink($image_path)) {
            echo "Imagen eliminada: " . $image_path . "<br>";
        } else {
            echo "Error al eliminar la imagen: " . $image_path . "<br>";
        }
    }
}

// Eliminar el ítem de la base de datos
$query = "DELETE FROM item WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);
$execute = mysqli_stmt_execute($stmt);

if ($execute) {
    echo "Ítem eliminado con éxito.";
} else {
    echo "Error al eliminar el ítem.";
}

// Redirigir al usuario a la página principal o a la lista de ítems
header("Location: ../pages/index.php"); // Redirige donde necesites
exit;
?>