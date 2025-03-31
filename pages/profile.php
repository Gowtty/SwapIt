<?php
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/checkSession.php';

// Obtener datos del usuario desde la base de datos
$usuario_id = $_SESSION['user_id'];
$query = "SELECT id, username, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Obtener los swaps del usuario
$query_swaps = "SELECT id, title, description, images, created_at, status FROM item WHERE user_id = ? ORDER BY created_at DESC";
$stmt_swaps = $conn->prepare($query_swaps);
$stmt_swaps->bind_param("i", $usuario_id);
$stmt_swaps->execute();
$result_swaps = $stmt_swaps->get_result();
$swaps = array();
while ($row = $result_swaps->fetch_assoc()) {
    $swaps[] = $row;
}
$stmt_swaps->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - SwapIt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .profile-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
        }
        .sidebar {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: #6c757d;
            border: 3px solid #0d6efd;
        }
        .sidebar-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            text-align: center;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin-bottom: 0.8rem;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 1.2rem;
            color: #666;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .sidebar-menu a i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        .sidebar-menu a:hover {
            background: #e7f1ff;
            color: #0d6efd;
            transform: translateX(5px);
        }
        .profile-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .profile-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }
        .profile-title {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        .profile-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #0d6efd;
            border-radius: 2px;
        }
        .profile-info {
            display: grid;
            gap: 1.2rem;
            max-width: 400px;
            margin: 0 auto;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }
        .profile-info p {
            margin: 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .profile-info i {
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .profile-info strong {
            color: #333;
            margin-right: 0.5rem;
        }
        .edit-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            margin-top: 2rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .edit-profile-btn:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }
        .stats-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .search-section {
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }
        .search-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .search-title {
            font-size: 1.2rem;
            color: #333;
            font-weight: 600;
        }
        .search-input-group {
            position: relative;
            max-width: 400px;
            width: 100%;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .search-input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .item-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .item-card:hover {
            transform: translateY(-5px);
        }
        .item-image {
            height: 160px;
            object-fit: contain;
            width: 100%;
            background-color: #f8f9fa;
            padding: 0.5rem;
        }
        .item-info {
            padding: 1rem;
        }
        .item-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .item-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #666;
            font-size: 0.9rem;
        }
        .item-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            gap: 0.5rem;
        }
        .status-available {
            background: #e7f5ff;
            color: #0d6efd;
        }
        .status-reserved {
            background: #fff3cd;
            color: #856404;
        }
        .status-exchanged {
            background: #e2e3e5;
            color: #383d41;
        }
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            .stats-section {
                grid-template-columns: 1fr;
            }
            .search-header {
                flex-direction: column;
                gap: 1rem;
            }
            .search-input-group {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-grid">
            <div class="sidebar">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="sidebar-title">Mi Cuenta</h2>
                <ul class="sidebar-menu">
                    <li><a href="../pages/my-items.php"><i class="fas fa-box"></i> Mis Artículos</a></li>
                    <li><a href="create-item.php"><i class="fas fa-plus-circle"></i> Subir artículo</a></li>
                    <li><a href="../pages/my-offers.php"><i class="fas fa-exchange-alt"></i> Mis Ofertas</a></li>
                    <li><a href="../pages/user-config.php"><i class="fas fa-cog"></i> Configuración</a></li>
                    <li><a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </div>

            <div class="profile-content">
                <div class="profile-header">
                    <h1 class="profile-title">Bienvenido, <?php echo htmlspecialchars($user['username']); ?>!</h1>
                    <div class="profile-info">
                        <p><i class="fas fa-envelope"></i><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><i class="fas fa-calendar-alt"></i><strong>Miembro desde:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                    </div>
                    <a href="../pages/user-config.php" class="edit-profile-btn">
                        <i class="fas fa-edit"></i> Editar Perfil
                    </a>
                </div>

                <div class="search-section">
                    <div class="search-header">
                        <h2 class="search-title">Mis Artículos</h2>
                    </div>
                    <div class="items-grid" id="itemsGrid">
                        <?php foreach ($swaps as $item): 
                            $images = json_decode($item['images'], true);
                            $firstImage = !empty($images) ? $images[0] : '../src/assets/default.png';
                            $statusClass = '';
                            switch($item['status']) {
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
                            <div class="item-card" data-title="<?php echo htmlspecialchars($item['title']); ?>">
                                <img src="<?php echo htmlspecialchars($firstImage); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image">
                                <div class="item-info">
                                    <h3 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($item['created_at'])); ?></span>
                                        <span class="item-status <?php echo $statusClass; ?>">
                                            <i class="fas fa-circle"></i> <?php echo htmlspecialchars($item['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="stats-section">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($swaps); ?></div>
                        <div class="stat-label">Artículos Publicados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Ofertas Activas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Intercambios Realizados</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.item-card');
            const itemsGrid = document.getElementById('itemsGrid');
            let hasVisibleItems = false;

            items.forEach(item => {
                const title = item.dataset.title.toLowerCase();
                if (title.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasVisibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });

            if (!hasVisibleItems) {
                itemsGrid.innerHTML = '<div class="no-results">No se encontraron artículos que coincidan con tu búsqueda</div>';
            }
        });
    </script>

    <?php include '../php/footer.php'; ?>
</body>
</html>