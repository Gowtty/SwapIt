<?php
session_start();
include '../php/connectDB.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Obtener historial de swaps (ejemplo simple)
$usuario_id = $_SESSION['id'];
$query = "SELECT * FROM swap_history WHERE user_id = ? ORDER BY swap_date DESC";
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
    <title>Historial de Swaps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../php/header-session.php'; ?>
<div class="container mt-5">
    <h2>Historial de Swaps</h2>
    <?php if (count($historial) > 0): ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $swap): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($swap['id']); ?></td>
                        <td><?php echo htmlspecialchars($swap['title']); ?></td>
                        <td><?php echo htmlspecialchars($swap['swap_date']); ?></td>
                        <td><?php echo htmlspecialchars($swap['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes historial de swaps aún.</p>
    <?php endif; ?>
</div>
<?php include '../php/footer.php'; ?>
</body>
</html>
