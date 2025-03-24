<?php
session_start();
include '../php/connectDB.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    <title>Mis Swaps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../php/header-session.php'; ?>
<div class="container mt-5">
    <h2>Mis Swaps</h2>
    <?php if (count($historial) > 0): ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $swap): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($swap['id']); ?></td>
                        <td>
                            <a href="item.php?id=<?php echo htmlspecialchars($swap['id']); ?>">
                            <?php echo htmlspecialchars($swap['title']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($swap['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($swap['status']); ?></td>
                        <?php 
                            $imagenes = json_decode($swap['images'], true); // Convertimos JSON a array para mostrar las imagenes
                            $imagenPrincipal = $imagenes[0] ?? '../src/default.jpg'; // Tomamos la primera imagen del array
                        ?>
                        <td><img src="<?php echo htmlspecialchars($imagenPrincipal); ?>" alt="Imagen del swap" width="100"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes historial de swaps aun.</p>
    <?php endif; ?>
</div>
<?php include '../php/footer.php'; ?>
</body>
</html>
