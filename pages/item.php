<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';


// Verificar si el parámetro ID está presente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de ítem no proporcionado.";
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
    echo "Ítem no encontrado.";
    exit;
}

$images = json_decode($item['images'], true);
$isOwner = ($user_id == $item['owner_id']);
?>
<head>
    <title><?php echo htmlspecialchars($item['title']); ?></title>
</head>

<body>
    <h1><?php echo htmlspecialchars($item['title']); ?></h1>
    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($item['category_name']); ?></p>
    <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
    <p><strong>Subcategoría:</strong> <?php echo htmlspecialchars($item['subcategory_name']); ?></p>
    <p><strong>Estado:</strong> <?php echo htmlspecialchars($item['item_status']); ?></p>

    <?php if (!empty($images)): ?>
        <h2>Imágenes</h2>
        <?php 
            array_shift($images);
            foreach ($images as $image): ?>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Imagen del ítem" style="max-width: 300px;">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Información del usuario -->
    <div class="user-info">
        <h3>Publicado por:</h3>
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($item['username']); ?></p>
    </div>

    <!-- Opciones según si el usuario es el dueño o no -->
    <div class="actions">
        <?php if ($isOwner): ?>
            <a href="../pages/edit-item.php?id=<?php echo $item_id; ?>">Editar</a>
            <a href="../pages/delete-item.php?id=<?php echo $item_id; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este ítem?');">Eliminar</a>
        <?php else: ?>
            <a href="../pages/make-offer.php?id=<?php echo $item_id; ?>">Hacer oferta</a>
        <?php endif; ?>
    </div>

    <a href="../pages/index.php">Volver a la lista</a>

    <?php include '../php/footer.php'; ?>
</body>