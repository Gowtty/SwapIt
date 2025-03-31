<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';

// Verificar si el parámetro ID está presente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo("ID de ítem no proporcionado.");
    header("Location: index.php");
    exit;
}

$item_id = intval($_GET['id']); // Asegurar que el ID es un número
$user_id = $_SESSION['user_id'] ?? null;

$query = "
    SELECT item.*, 
           categories.name AS category_name, 
           subcategories.name AS subcategory_name, 
           users.username,
           users.id AS owner_id,
           item.status AS item_status
    FROM item
    LEFT JOIN categories ON item.category_id = categories.id
    LEFT JOIN categories AS subcategories ON item.subcategory_id = subcategories.id
    LEFT JOIN users ON item.user_id = users.id
    WHERE item.id = ?";
    
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

// Verificar si el ítem existe
if (!$item) {
    echo("Ítem no encontrado.");
    header("Location: index.php");
    exit;
}

$images = json_decode($item['images'], true);
$isOwner = ($user_id == $item['owner_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['title']); ?> - SwapIt</title>
    <?php include '../php/notifications.php'; ?>
    <style>
        .item-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .item-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .item-header {
            padding: 2rem;
            border-bottom: 1px solid #dee2e6;
        }
        .item-title {
            font-size: 2rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 1rem;
        }
        .item-meta {
            display: flex;
            gap: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .item-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .item-content {
            padding: 2rem;
        }
        .item-description {
            color: #495057;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .item-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .item-image {
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .item-image:hover {
            transform: scale(1.02);
        }
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .user-info h3 {
            color: #212529;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        .user-info p {
            margin: 0;
            color: #6c757d;
        }
        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #bb2d3b;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5c636a;
            transform: translateY(-2px);
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #0d6efd;
            text-decoration: none;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.6rem 0.7rem;
            transition: all 0.3s ease-in-out;
        }
        .badge:hover{
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
            cursor: pointer;
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
    <div class="item-container">
        <div class="item-card">
            <div class="item-header">
                <h1 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h1>
                <div class="item-meta">
                    <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($item['category_name']); ?></span>
                    <span><i class="fas fa-layer-group"></i> <?php echo htmlspecialchars($item['subcategory_name']); ?></span>
                    <?php if ($item['item_status'] == 'Disponible'): ?>
                        <span class="badge bg-success">Disponible</span>
                    <?php elseif ($item['item_status'] == 'Reservado'): ?>
                        <span class="badge bg-warning">Reservado</span>
                    <?php elseif ($item['item_status'] == 'Intercambiado'): ?>
                            <span class="badge bg-secondary">Intercambiado</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="item-content">
                <div class="item-description">
                    <?php echo nl2br(htmlspecialchars($item['description'])); ?>
                </div>

                <?php if (!empty($images)): ?>
                    <div class="item-images">
                        <?php 
                        array_shift($images);
                        foreach ($images as $image): ?>
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Imagen del ítem">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="user-info">
                    <h3><i class="fas fa-user me-2"></i>Publicado por</h3>
                    <p>
                        <a href="view-profile.php?id=<?php echo $item['owner_id']; ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($item['username']); ?>
                        </a>
                    </p>
                </div>

                <div class="actions">
                    <?php if ($isOwner): ?>
                        <a href="../pages/edit-item.php?id=<?php echo $item_id; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i>Editar
                        </a>
                        <button onclick="confirmDelete(<?php echo $item_id; ?>)" class="btn btn-danger">
                            <i class="fas fa-trash"></i>Eliminar
                        </button>
                    <?php else: ?>
                        <a href="../pages/make-offer.php?id=<?php echo $item_id; ?>" class="btn btn-primary">
                            <i class="fas fa-handshake"></i>Hacer oferta
                        </a>
                    <?php endif; ?>
                </div>

                <a href="../pages/index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>Volver a la lista
                </a>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(itemId) {
            showConfirmationDialog(
                'Eliminar artículo',
                '¿Estás seguro de que deseas eliminar este artículo? Esta acción no se puede deshacer.',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '../php/delete-item.php';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'item_id';
                    input.value = itemId;
                    
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        // Add event listener for form submission
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Create a temporary div to parse the HTML
                        const temp = document.createElement('div');
                        temp.innerHTML = html;
                        
                        // Find and execute any scripts in the response
                        const scripts = temp.getElementsByTagName('script');
                        for (let script of scripts) {
                            eval(script.innerHTML);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al eliminar el artículo', 'error');
                    });
                });
            });
        });
    </script>

    <?php include '../php/footer.php'; ?>
</body>
</html>