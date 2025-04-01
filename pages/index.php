<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';

// Obtener las últimas 5 publicaciones de la base de datos
$query = "SELECT id, title, images FROM item ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conn, $query);
$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - SwapIt</title>
</head>

<style>

    .hover-lift {
        transition: transform 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card-img-top {
        height: 160px;
        object-fit: contain;
        width: 100%;
        background-color: #f8f9fa;
        padding: 0.5rem;
    }
    .card-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .card-text {
        flex-grow: 1;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
 
</style>
<div class="container-fluid py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Bienvenido a SwapIt</h1>
                <p class="lead mb-4">Tu plataforma de confianza para intercambiar artículos de manera segura y fácil.</p>
                <div class="d-flex gap-3">
                    <a href="create-item.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-upload me-2"></i>Subir Artículo
                    </a>
                    <a href="aboutus.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Conocer más
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="../src/assets/hero.png" alt="SwapIt Hero" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="mb-5">
            <h2 class="h3 mb-4">Publicaciones recientes</h2>
            <div class="row g-4">
                <?php
                $query = "SELECT i.*, u.username, c.name as category_name 
                         FROM item i 
                         JOIN users u ON i.user_id = u.id 
                         LEFT JOIN categories c ON i.category_id = c.id 
                         WHERE i.status = 'Disponible' 
                         ORDER BY i.created_at DESC 
                         LIMIT 6";
                
                $result = mysqli_query($conn, $query);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($item = mysqli_fetch_assoc($result)) {
                        $images = json_decode($item['images'], true);
                        $firstImage = !empty($images) ? $images[0] : '../src/default.jpg';
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo htmlspecialchars($firstImage); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="card-text text-muted">
                                        <small>
                                            <i class="fas fa-user me-1"></i>
                                            <a href="view-profile.php?id=<?php echo $item['user_id']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($item['username']); ?>
                                            </a>
                                        </small>
                                    </p>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($item['description'], 0, 100)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($item['category_name'] ?? 'Sin categoría'); ?></span>
                                        <a href="item.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-primary">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12"><p class="text">No hay artículos disponibles en este momento.</p></div>';
                }
                ?>
            </div>
        </div>
        <div class="mb-5">
            <h2 class="h3 mb-4">Categorías Populares</h2>
            <div class="row g-4">
                <?php
                $query = "SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY name";
                $result = mysqli_query($conn, $query);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($category = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col-md-4 col-lg-2">
                            <a href="search.php?category=<?php echo urlencode($category['id']); ?>" 
                               class="card text-decoration-none text-center h-100 shadow-sm hover-lift">
                                <div class="card-body">
                                    <i class="fas fa-folder fa-2x text-primary mb-2"></i>
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($category['name']); ?></h5>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12"><p class="text-muted">No hay categorías disponibles.</p></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../php/footer.php'; ?>