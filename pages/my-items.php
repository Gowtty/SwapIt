<?php
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/notifications.php';

// Obtener historial de swaps de la tabla items
$usuario_id = $_SESSION['user_id'];

$query = "SELECT * FROM item WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$historial = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Artículos - SwapIt</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
            padding: 2rem 1.5rem 1rem;
        }
        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        .card-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .table td {
            vertical-align: middle;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background: #bb2d3b;
            transform: translateY(-2px);
        }
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .status-available {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-reserved {
            background: #fff3cd;
            color: #856404;
        }
        .status-exchanged {
            background: #e2e3e5;
            color: #383d41;
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
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <i class="fas fa-box text-primary me-2"></i>Mis Artículos
                </h1>
                <p class="card-subtitle">Gestiona tus publicaciones y ofertas</p>
            </div>
            <div class="card-body">
                <?php if (count($historial) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial as $swap): ?>
                                    <tr>
                                        <?php 
                                        $imagenes = json_decode($swap['images'], true);
                                        $imagenPrincipal = $imagenes[0] ?? '../src/assets/default.png';
                                        ?>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($imagenPrincipal); ?>" 
                                                 alt="Imagen del artículo" 
                                                 class="item-image">
                                        </td>
                                        <td><?php echo htmlspecialchars($swap['title']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($swap['created_at'])); ?></td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            switch($swap['status']) {
                                                case 'Disponible':
                                                    $statusClass = 'status-available';
                                                    break;
                                                case 'Reservado':
                                                    $statusClass = 'status-reserved';
                                                    break;
                                                case 'Intercambiado':
                                                    $statusClass = 'status-exchanged';
                                                    break;
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($swap['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="item.php?id=<?php echo htmlspecialchars($swap['id']); ?>" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-item.php?id=<?php echo htmlspecialchars($swap['id']); ?>" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmDelete(<?php echo htmlspecialchars($swap['id']); ?>)" 
                                                        class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes artículos publicados aún.</p>
                        <a href="create-item.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Publicar un artículo
                        </a>
                    </div>
                <?php endif; ?>
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
