<?php
session_start();
include '../php/connectDB.php'; 
include '../php/resizeImage.php';
include 'checkSession.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category'] ?? '';
    $subcategory_id = $_POST['subcategory'] ?? '';
    
    // Validate required fields
    if (empty($title) || empty($description) || empty($category_id)) {
        $_SESSION['notification'] = [
            'message' => 'Por favor, completa todos los campos requeridos.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/create-item.php';</script>";
        exit;
    }
    
    // Subir las imágenes
    $image_paths = [];
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $image_count = count($_FILES['image']['name']);
        
        // Crear un array para almacenar las rutas de las imágenes subidas
        for ($i = 0; $i < $image_count; $i++) {
            $target_dir = "../src/uploads/";
            $target_file = $target_dir . basename($_FILES['image']['name'][$i]);
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($image_file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $target_file)) {
                    //La primera imagen, la redimensionamos a miniatura
                    if ($i == 0) {
                        // Creamos la miniatura en una ruta separada
                        $thumbnail_path = $target_dir . 'thumb_' . basename($_FILES['image']['name'][$i]);
                        resizeImage($target_file, $thumbnail_path, 200, 200); // Tamaño a redimensionar
                        $image_paths[] = $thumbnail_path; // Guardar miniatura como la primera imagen
                    }
                    
                    $image_paths[] = $target_file; // Guardar imagenes sin redimensionar
                } else {
                    $_SESSION['notification'] = [
                        'message' => 'Error al subir la imagen.',
                        'type' => 'error'
                    ];
                    echo "<script>window.location.href = '../pages/create-item.php';</script>";
                    exit;
                }
            } else {
                $_SESSION['notification'] = [
                    'message' => 'Solo se permiten imágenes con los siguientes formatos: jpg, jpeg, png, gif.',
                    'type' => 'error'
                ];
                echo "<script>window.location.href = '../pages/create-item.php';</script>";
                exit;
            }
        }
    }

    if (empty($image_paths)) {
        $_SESSION['notification'] = [
            'message' => 'Por favor, sube al menos una imagen del artículo.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/create-item.php';</script>";
        exit;
    }

    // Convertir el array de imágenes a formato JSON
    $image_paths_json = json_encode($image_paths);

    // Preparar la consulta SQL para insertar la publicación
    $query = "INSERT INTO item (user_id, title, description, category_id, subcategory_id, images, status) 
              VALUES (?, ?, ?, ?, ?, ?, 'Disponible')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'isssss', $user_id, $title, $description, $category_id, $subcategory_id, $image_paths_json);

    if (mysqli_stmt_execute($stmt)) {
        $item_id = mysqli_insert_id($conn);
        $_SESSION['notification'] = [
            'message' => '¡Artículo publicado exitosamente!',
            'type' => 'success'
        ];
        echo "<script>window.location.href = '../pages/item.php?id=" . $item_id . "';</script>";
        exit;
    } else {
        $_SESSION['notification'] = [
            'message' => 'Error al publicar el artículo. Por favor, intenta nuevamente.',
            'type' => 'error'
        ];
        echo "<script>window.location.href = '../pages/create-item.php';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href = '../pages/create-item.php';</script>";
    exit;
}
?>