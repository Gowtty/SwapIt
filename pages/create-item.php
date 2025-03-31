<?php 
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/checkSession.php'; 
include '../php/notifications.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar artículo - SwapIt</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .create-item-container {
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
        <div class="create-item-container">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>Publicar Nuevo Artículo
                    </h2>
                    <p class="text-muted mt-2">Comparte lo que ya no uses y encuentra algo nuevo</p>
                </div>
                <div class="card-body">
                    <form id="item-form" action="../php/item-form.php" method="POST" enctype="multipart/form-data">
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
                                       placeholder="Ej: iPhone 12 Pro Max"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" 
                                      name="description" 
                                      id="description" 
                                      rows="4" 
                                      placeholder="Describe tu artículo..."
                                      required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Seleccione una categoría</option>
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
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subcategory" class="form-label">Subcategoría</label>
                            <select class="form-control" id="subcategory" name="subcategory" required>
                                <option value="">Seleccione una subcategoría</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Fotografías</label>
                            <div class="file-input-wrapper">
                                <label class="file-input-label">
                                    <i class="fas fa-camera me-2"></i>Seleccionar imágenes
                                </label>
                                <input type="file" 
                                       name="image[]" 
                                       id="image" 
                                       multiple 
                                       accept="image/*"
                                       required>
                            </div>
                            <div class="image-preview" id="imagePreview"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Publicar Artículo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
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