<?php
include '../php/connectDB.php';
session_start();

if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true) {
    // Si el usuario está logueado, muestra el header con opciones de usuario
    include('../php/header-session.php');
} else {
    // Si el usuario no está logueado, muestra el header normal
    include('../php/header.php');
}

// Verificar si el parámetro ID está presente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de ítem no proporcionado.";
    exit;
}

$item_id = intval($_GET['id']); // Asegurar que el ID es un número

// Obtener los detalles del ítem desde la base de datos
$query = "SELECT item.*, categories.name AS category_name, users.username
          FROM item 
          LEFT JOIN categories ON item.category_id = categories.id
          LEFT JOIN users ON item.user_id = users.id
          WHERE item.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

// Verificar si el ítem existe
if ($result->num_rows === 0) {
    echo "Ítem no encontrado.";
    exit;
}

$images = json_decode($item['images'], true);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['title']); ?></title>
</head>

<body>
    <h1><?php echo htmlspecialchars($item['title']); ?></h1>
    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($item['category_name']); ?></p>
    <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($item['description'])); ?></p>

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

    <a href="../pages/index.php">Volver a la lista</a>

    <?php include '../php/footer.php'; ?>
</body>