<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';

// Obtener las últimas 5 publicaciones de la base de datos
$query = "SELECT id, title, images FROM item ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conn, $query);
$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container-fluid py-5">
    <div class="container">
        <!-- Hero Section -->
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

        <!-- Featured Items Section -->
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
                                     alt="<?php echo htmlspecialchars($item['title']); ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="card-text text-muted">
                                        <small>
                                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($item['username']); ?>
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

        <!-- Categories Section -->
        <div class="mb-5">
            <h2 class="h3 mb-4">Categorías Populares</h2>
            <div class="row g-4">
                <?php
                $categories = ['Electrónica', 'Ropa y Moda', 'Libros', 'Hogar', 'Muebles'];
                foreach ($categories as $category) {
                    ?>
                    <div class="col-md-4 col-lg-2">
                        <a href="search.php?category=<?php echo urlencode($category); ?>" 
                           class="card text-decoration-none text-center h-100 shadow-sm hover-lift">
                            <div class="card-body">
                                <i class="fas fa-folder fa-2x text-primary mb-2"></i>
                                <h5 class="card-title mb-0"><?php echo $category; ?></h5>
                            </div>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-star fa-3x text-primary mb-3"></i>
                        <h3 class="h5">Sistema de Calificaciones</h3>
                        <p class="text-muted">Evalúa y confía en otros usuarios.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>

<?php include '../php/footer.php'; ?>