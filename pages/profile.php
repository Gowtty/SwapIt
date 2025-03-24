<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/login-verify.php';

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
$query_swaps = "SELECT id, title, description, images, created_at FROM item WHERE id = ? ORDER BY created_at DESC";
$stmt_swaps = $conn->prepare($query_swaps);
$stmt_swaps->bind_param("i", $usuario_id);
$stmt_swaps->execute();
$result_swaps = $stmt_swaps->get_result();
$swaps = $result_swaps->fetch_all(MYSQLI_ASSOC);
$stmt_swaps->close();
?>
<head>
    <title>Mi Perfil</title>
</head>
<body>
    <div>
        <div>
<div>
    <div>
        <a href="#perfil">Perfil</a>
        <div>
            <button>Mi Cuenta</button>
            <ul>
                <li><a href="profile.php#mis-swaps">Mis Swaps</a></li>
                <li><a href="create-item.php">Crear Swap</a></li>
                <li><a href="../pages/swaphistory.php">Historial de Swaps</a></li>
                <li><a href="../pages/my-offers.php">Ofertas recibidas</a></li>
                <li><a href="../pages/user-config.php">Configuración</a></li>
                <li><hr></li>
                <li><a href="../php/logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
</div>

<div>
    <div id="perfil">
        <div>
            <h3>Bienvenido, <?php echo htmlspecialchars($user['username']); ?>!</h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Miembro desde:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
            <a href="#">Editar Perfil</a>
        </div>
    </div>

    <div id="mis-swaps">
        <div>
            <div>
                <h4>Mis Swaps</h4>
            </div>
            <div>
                <?php if (count($swaps) > 0): ?>
                    <?php foreach ($swaps as $swap): ?>
                        <div>
                            <div>
                                <?php if (isset($swap['images'])): ?>
                                    <img src="<?php echo htmlspecialchars($swap['images']); ?>" alt="<?php echo htmlspecialchars($swap['title']); ?>">
                                <?php endif; ?>
                                <div>
                                    <h5 ><?php echo htmlspecialchars($swap['title']); ?></h5>
                                    <p><?php echo htmlspecialchars($swap['description']); ?></p>
                                    <p>Publicado el: <?php echo htmlspecialchars($swap['created_at']); ?></p>
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