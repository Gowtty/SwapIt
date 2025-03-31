<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$items = array();

if (!empty($search_query)) {
    $query = "SELECT i.*, u.username 
              FROM item i 
              JOIN users u ON i.user_id = u.id 
              WHERE i.title LIKE ? OR i.description LIKE ? 
              AND i.status = 'Disponible'
              ORDER BY i.created_at DESC";
    $search_param = "%{$search_query}%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar - SwapIt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .search-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .search-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .search-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .search-input-group {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            font-size: 1.1rem;
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
            font-size: 1.2rem;
        }
        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
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
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #e9ecef;
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
        .item-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .item-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #666;
            font-size: 0.9rem;
        }
        .item-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .item-user i {
            color: #0d6efd;
        }
        .view-item-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .view-item-btn:hover {
            background: #0b5ed7;
        }
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-style: italic;
        }
        .search-stats {
            text-align: center;
            color: #666;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .search-results {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="search-container">
        <div class="search-header">
            <h1 class="search-title">Buscar Artículos</h1>
            <form action="search.php" method="GET" class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="q" class="search-input" placeholder="Buscar artículos..." value="<?php echo htmlspecialchars($search_query); ?>">
            </form>
        </div>

        <?php if (!empty($search_query)): ?>
            <div class="search-stats">
                <?php echo count($items); ?> resultados para "<?php echo htmlspecialchars($search_query); ?>"
            </div>
        <?php endif; ?>

        <div class="search-results">
            <?php if (empty($items)): ?>
                <div class="no-results">
                    <?php if (!empty($search_query)): ?>
                        No se encontraron artículos que coincidan con tu búsqueda
                    <?php else: ?>
                        Ingresa un término de búsqueda para encontrar artículos
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): 
                    $images = json_decode($item['images'], true);
                    $firstImage = !empty($images) ? $images[0] : '../src/assets/default.png';
                ?>
                    <div class="item-card">
                        <img src="<?php echo htmlspecialchars($firstImage); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image">
                        <div class="item-info">
                            <h3 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="item-description"><?php echo htmlspecialchars($item['description']); ?></p>
                            <div class="item-meta">
                                <div class="item-user">
                                    <i class="fas fa-user"></i>
                                    <span><?php echo htmlspecialchars($item['username']); ?></span>
                                </div>
                                <a href="item.php?id=<?php echo $item['id']; ?>" class="view-item-btn">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../php/footer.php'; ?>
</body>
</html> 