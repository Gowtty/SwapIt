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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Publicación - SwapIt</title>
    <?php include '../php/notifications.php'; ?>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .edit-item-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
            padding: 2rem 1.5rem 1rem;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            width: 100%;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
        }
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #dc3545;
        }
        .remove-image:hover {
            background: #dc3545;
            color: white;
        }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .file-input-label {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-input-label:hover {
            background: #e9ecef;
            border-color: #0d6efd;
        }
        .back-button {
            display: inline-block;
            margin-top: 1rem;
            color: #0d6efd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .back-button:hover {
            color: #0a58ca;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['notification'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('" . htmlspecialchars($_SESSION['notification']['message']) . "', '" . $_SESSION['notification']['type'] . "');
            });
        </script>";
        unset($_SESSION['notification']);
    }
    ?>
    <div class="container">
        <div class="edit-item-container">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>Editar Publicación
                    </h2>
                    <p class="text-muted mt-2">Actualiza los detalles de tu artículo</p>
                </div>
                <div class="card-body">
                    <form id="item-form" action="../php/edit-item-form.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Nombre de la publicación</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-heading"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       value="<?php echo htmlspecialchars($item['title']); ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" 
                                      name="description" 
                                      id="description" 
                                      rows="4" 
                                      required><?php echo htmlspecialchars($item['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Seleccione una categoría</option>
                                <?php
                                $queryCategories = "SELECT id, name FROM categories WHERE parent_id IS NULL";
                                $resultCategories = $conn->query($queryCategories);
                                while ($row = $resultCategories->fetch_assoc()) {
                                    $selected = $row['id'] == $item['category_id'] ? 'selected' : '';
                                    echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subcategory" class="form-label">Subcategoría</label>
                            <select class="form-control" id="subcategory" name="subcategory" required>
                                <option value="">Seleccione una subcategoría</option>
                                <?php
                                while ($row = mysqli_fetch_assoc($resultSubcategories)) {
                                    $selected = $row['id'] == $item['subcategory_id'] ? 'selected' : '';
                                    echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="item_status" class="form-label">Estado</label>
                            <select class="form-control" name="item_status" id="item_status" required>
                                <?php foreach ($enum_values as $value): ?>
                                    <option value="<?= $value ?>" <?= ($item['item_status'] == $value) ? 'selected' : '' ?>>
                                        <?= ucfirst($value) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Fotografías</label>
                            <div class="file-input-wrapper">
                                <label class="file-input-label">
                                    <i class="fas fa-camera me-2"></i>Seleccionar nuevas imágenes
                                </label>
                                <input type="file" 
                                       name="image[]" 
                                       id="image" 
                                       multiple 
                                       accept="image/*">
                            </div>
                            <div class="image-preview" id="imagePreview">
                                <?php
                                $images = json_decode($item['images'], true);
                                if ($images) {
                                    foreach ($images as $image) {
                                        echo '<div class="preview-item">';
                                        echo '<img src="' . htmlspecialchars($image) . '" alt="Preview">';
                                        echo '<button type="button" class="remove-image" onclick="this.parentElement.remove()">';
                                        echo '<i class="fas fa-times"></i>';
                                        echo '</button>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Actualizar Publicación
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="item.php?id=<?php echo $item_id; ?>" class="back-button">
                            <i class="fas fa-arrow-left me-1"></i>Volver al ítem
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            
            [...e.target.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-image" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
    <script src="../js/create-item.js"></script>
</body>

<?php include '../php/footer.php'; ?>