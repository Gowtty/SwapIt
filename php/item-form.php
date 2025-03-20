<?php
session_start();
include 'connectDB.php'; // Incluye la conexión a la base de datos

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $subcategory_id = $_POST['subcategory'];
    
    // Subir las imágenes
    $image_paths = [];
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $image_count = count($_FILES['image']['name']);
        
        // Crear un array para almacenar las rutas de las imágenes subidas
        for ($i = 0; $i < $image_count; $i++) {
            $target_dir = "../src/uploads/"; // Directorio de destino
            $target_file = $target_dir . basename($_FILES['image']['name'][$i]);
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Validar tipo de imagen
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($image_file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $target_file)) {
                    $image_paths[] = $target_file;
                } else {
                    echo "Error al subir la imagen.";
                }
            } else {
                echo "Solo se permiten imágenes con los siguientes formatos: jpg, jpeg, png, gif.";
            }
        }
    }

    // Convertir el array de imágenes a formato JSON
    $image_paths_json = json_encode($image_paths);

    // Preparar la consulta SQL para insertar la publicación
    if (!empty($title) && !empty($description) && !empty($category_id)) {
        $query = "INSERT INTO item (user_id, title, description, category_id, subcategory_id, images) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'isssss', $user_id, $title, $description, $category_id, $subcategory_id, $image_paths_json);
        mysqli_stmt_execute($stmt);

        // Redirigir a una página de éxito o a la lista de publicaciones
        header("Location: ../pages/index.php"); // Cambia esto según tu flujo
        exit;
    } else {
        echo "Por favor, complete todos los campos.";
    }
}
?>