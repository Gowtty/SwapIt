<?php
session_start();
include '../php/connectDB.php'; 
include '../php/resizeImage.php';


$user_id = $_SESSION['user_id'];

// Asegurarse de que el ID del ítem es proporcionado
$item_id = $_POST['item_id'] ?? null;

if (!$item_id) {
    echo "No se ha proporcionado el ID del ítem.";
    exit;
}

// Obtener los datos actuales del ítem desde la base de datos
$query = "SELECT * FROM item WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo "Ítem no encontrado o no tienes permiso para editarlo.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $subcategory_id = $_POST['subcategory'];

    // Obtener las imágenes actuales y eliminarlas
    $current_images = json_decode($item['images'], true);
    if ($current_images && is_array($current_images)) {
        foreach ($current_images as $image) {
            // Eliminar las imágenes anteriores del servidor
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }

    $image_paths = [];

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $image_count = count($_FILES['image']['name']);
        $target_dir = "../src/uploads/";

        for ($i = 0; $i < $image_count; $i++) {
            $target_file = $target_dir . basename($_FILES['image']['name'][$i]);
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($image_file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $target_file)) {
                    // Si es la primera imagen, la redimensionamos a miniatura
                    if ($i == 0) {
                        $thumbnail_path = $target_dir . 'thumb_' . basename($_FILES['image']['name'][$i]);
                        resizeImage($target_file, $thumbnail_path, 200, 200);
                        $image_paths[0] = $thumbnail_path; // Reemplazamos la primera imagen (miniatura)
                    }
                    $image_paths[] = $target_file;
                } else {
                    echo "Error al subir la imagen.";
                }
            } else {
                echo "Solo se permiten imágenes con los siguientes formatos: jpg, jpeg, png, gif.";
            }
        }
    }

    $image_paths_json = json_encode($image_paths);

    $query = "UPDATE item SET title=?, description=?, category_id=?, subcategory_id=?, images=? WHERE id=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $title, $description, $category_id, $subcategory_id, $image_paths_json, $item_id, $user_id);
    mysqli_stmt_execute($stmt);

    header("Location: ../pages/item.php?id=" . $item_id);
    exit;
}
?>