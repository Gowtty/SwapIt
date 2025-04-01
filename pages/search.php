<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$items = array();

// Build the base query
$query = "SELECT i.*, u.username, c.name as category_name 
          FROM item i 
          JOIN users u ON i.user_id = u.id 
          LEFT JOIN categories c ON i.category_id = c.id 
          WHERE i.status = 'Disponible'";

$params = array();
$types = "";

// Add search condition if there's a search query
if (!empty($search_query)) {
    $query .= " AND (i.title LIKE ? OR i.description LIKE ?)";
    $search_param = "%{$search_query}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

// Add category condition if a category is selected
if ($category_id) {
    $query .= " AND i.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

$query .= " ORDER BY i.created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
$stmt->close();

// Get category name for display
$category_name = '';
if ($category_id) {
    $cat_query = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($cat_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $cat_result = $stmt->get_result();
    if ($cat_row = $cat_result->fetch_assoc()) {
        $category_name = $cat_row['name'];
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
        .search-form {
            display: flex;
            gap: 1rem;
            max-width: 800px;
            margin: 0 auto;
            align-items: center;
        }
        .search-input-group {
            position: relative;
            flex: 1;
        }
        .category-select {
            padding: 1rem;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            font-size: 1.1rem;
            background-color: white;
            color: #333;
            cursor: pointer;
            min-width: 200px;
            transition: all 0.3s ease;
        }
        .category-select:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        .category-select option {
            padding: 0.5rem;
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
            .search-form {
                flex-direction: column;
            }
            .category-select {
                width: 100%;
            }
            .search-results {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="search-container">
        <div class="search-header">
            <h1 class="search-title">
                <?php if ($category_name): ?>
                    Categoría: <?php echo htmlspecialchars($category_name); ?>
                <?php else: ?>
                    Buscar Artículos
                <?php endif; ?>
            </h1>
            
            <?php
            // Get only main categories (where parent_id is NULL) for the dropdown
            $categories_query = "SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY name";
            $categories_result = mysqli_query($conn, $categories_query);
            $categories = array();
            while ($row = mysqli_fetch_assoc($categories_result)) {
                $categories[] = $row;
            }
            ?>

            <form action="search.php" method="GET" class="search-form">
                <div class="search-input-group search-view">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" class="search-input" 
                           placeholder="Buscar artículos..." 
                           value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                <select name="category" class="category-select" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if (!empty($search_query) || $category_id): ?>
            <div class="search-stats">
                <?php 
                $search_text = !empty($search_query) ? " para \"{$search_query}\"" : "";
                $category_text = $category_name ? " en la categoría \"{$category_name}\"" : "";
                echo count($items) . " resultados" . $search_text . $category_text;
                ?>
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
                                    <a href="view-profile.php?id=<?php echo $item['user_id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($item['username']); ?>
                                    </a>
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