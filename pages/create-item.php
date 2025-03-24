<?php 
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/header-session.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir articulo</title>
</head>
<body>
    <form id="item-form" action = "../php/item-form.php" method = "POST" enctype="multipart/form-data">
        Nombre publicacion: <input type="text" id="title" name="title" required><br>
        Descripcion: <textarea name="description" id="description"></textarea><br>
        Categoria: <select id="category" name="category">
                        <option value="">Seleccione una categoria</option>
                        <?php
                        
                            $queryCategories = "SELECT id, name FROM categories WHERE parent_id IS NULL";
                            $resultCategories = $conn->query($queryCategories);

                            if ($resultCategories && $resultCategories->num_rows > 0) {
                                while ($row = $resultCategories->fetch_assoc()) {
                                    echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
                                }
                            } else {
                                echo "<option value=\"\">No se encontraron categorías</option>";
                            }
                        ?>
                    </select><br>
        Subcategoria:<select id="subcategory" name="subcategory">
                        <option value="">Seleccione una subcategoría</option>
                    </select><br>
        Fotografias: <input type="file" name="image[]" id="image" multiple required><br>

        <input type="submit" value="Publicar articulo">
    </form>
</body>
<?php include '../php/footer.php'; ?>
<script src="../js/create-item.js"></script>