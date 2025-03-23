<?php
session_start();
include '../php/connectDB.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Obtener datos del usuario desde la base de datos
$usuario_id = $_SESSION['id'];
$query = "SELECT id, username, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Obtener los swaps del usuario
$query_swaps = "SELECT id, title, description, images, created_at FROM item WHERE id = ? ORDER BY created_at DESC";
$stmt_swaps = $conn->prepare($query_swaps);
$stmt_swaps->bind_param("i", $usuario_id);
$stmt_swaps->execute();
$result_swaps = $stmt_swaps->get_result();
$swaps = $result_swaps->fetch_all(MYSQLI_ASSOC);
$stmt_swaps->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../php/header-session.php'; ?>
    <div class="container mt-4">
        <div class="row">
            <!-- Menú lateral -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#" class="list-group-item active">Perfil</a>
                    <div class="dropdown">
                        <button class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="dropdown">Mi Cuenta</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Mis Swaps</a></li>
                            <li><a class="dropdown-item" href="#">Historial de Swaps</a></li>
                            <li><a class="dropdown-item" href="#">Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Contenido del perfil -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Bienvenido, <?php echo htmlspecialchars($user['username']); ?>!</h3>
                        <p class="mt-3"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Miembro desde:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                        <a href="#" class="btn btn-primary">Editar Perfil</a>
                    </div>
                </div>
                
                <!-- Mis Swaps -->
                <div class="card">
                    <div class="card-body">
                        <h4>Mis Swaps</h4>
                        <div class="row">
                            <?php if (count($swaps) > 0): ?>
                                <?php foreach ($swaps as $swap): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <?php if (isset($swap['images'])): ?>
                                                <img src="<?php echo htmlspecialchars($swap['images']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($swap['title']); ?>">
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($swap['title']); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars($swap['description']); ?></p>
                                                <p class="text-muted">Publicado el: <?php echo htmlspecialchars($swap['created_at']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No tienes swaps registrados aún.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../php/footer.php'; ?>
</body>
</html>