<?php
include 'connectDB.php';

// Verificar si se ha enviado el parámetro category_id
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryId = $_GET['category'];

    // Consultar las subcategorías de la categoría seleccionada
    $querySubcategories = "SELECT id, name FROM categories WHERE parent_id = ?";
    $stmt = $conn->prepare($querySubcategories);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }

    // Retornar las subcategorías en formato JSON
    echo json_encode($subcategories);
}
?>