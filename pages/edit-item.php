<?php
session_start();
include '../php/login-verify.php'; 
include '../php/connectDB.php'; 
include '../php/checkSession.php'; 

// Verificar si el parámetro ID está presente y es válido
$item_id = $_GET['id'] ?? null;
if (!$item_id) {
    echo "ID de ítem no proporcionado.";
    exit;
}

// Obtener los datos del ítem para editarlo
$query = "SELECT item.*, categories.name AS category_name,
            item.status AS item_status
          FROM item
          LEFT JOIN categories ON item.category_id = categories.id
          WHERE item.id = ? AND item.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'ii', $item_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo "Ítem no encontrado o no tienes permiso para editarlo.";
    exit;
}

$querySubcategories = "SELECT id, name FROM categories WHERE parent_id = ?";
$stmtSubcategories = mysqli_prepare($conn, $querySubcategories);
mysqli_stmt_bind_param($stmtSubcategories, 'i', $item['category_id']);
mysqli_stmt_execute($stmtSubcategories);
$resultSubcategories = mysqli_stmt_get_result($stmtSubcategories);

// Obtener los valores del ENUM 'status'
$queryEnum = "SHOW COLUMNS FROM item LIKE 'status'";
$resultEnum = $conn->query($queryEnum);
$rowEnum = $resultEnum->fetch_assoc();
preg_match("/^enum\((.*)\)$/", $rowEnum['Type'], $matches);
$enum_values = str_getcsv($matches[1], ",", "'");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Publicación</title>
</head>

<body>
    <h1>Editar Publicación</h1>
    <form id="item-form" action="../php/edit-item-form.php" method="POST" enctype="multipart/form-data">
        Nombre publicación: <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required><br>

        Descripción: <textarea name="description" id="description" required><?php echo htmlspecialchars($item['description']); ?></textarea><br>

        Categoría:
        <select id="category" name="category" required>
            <option value="">Seleccione una categoría</option>
            <?php
                $queryCategories = "SELECT id, name FROM categories WHERE parent_id IS NULL";
                $resultCategories = $conn->query($queryCategories);
                while ($row = $resultCategories->fetch_assoc()) {
                    $selected = $row['id'] == $item['category_id'] ? 'selected' : '';
                    echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                }
            ?>
        </select><br>

        Subcategoría:
        <select id="subcategory" name="subcategory" required>
            <option value="">Seleccione una subcategoría</option>
            <?php
                while ($row = mysqli_fetch_assoc($resultSubcategories)) {
                    $selected = $row['id'] == $item['subcategory_id'] ? 'selected' : '';
                    echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                }
            ?>
        </select><br>

        Fotografías: 
        <input type="file" name="image[]" id="image" multiple><br>

        Estado: 
        <select name="item_status" id="item_status">
            <?php foreach ($enum_values as $value): ?>
                <option value="<?= $value ?>" <?= ($item['item_status'] == $value) ? 'selected' : '' ?>>
                    <?= ucfirst($value) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
        <input type="submit" value="Actualizar publicación">

       
    </form>
        

    <a href="item.php?id=<?php echo $item_id; ?>">Volver al ítem</a>
</body>

<?php include '../php/footer.php'; ?>
<script src="../js/create-item.js"></script>