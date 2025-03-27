<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/login-verify.php';

$user_id = $_SESSION['user_id'];

// Obtener las ofertas recibidas por los artículos del usuario
$query_received = "SELECT o.id AS offer_id, o.offered_by, o.item_id, o.offered_item_id, o.status, i.title AS item_title, offered_item.title AS offered_item_title
          FROM offers o
          JOIN item i ON o.item_id = i.id
          JOIN item offered_item ON o.offered_item_id = offered_item.id
          WHERE i.user_id = ?";
$stmt = mysqli_prepare($conn, $query_received);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$received_offers = mysqli_stmt_get_result($stmt);

// Obtener las ofertas realizadas por el usuario
$query_made = "SELECT o.id AS offer_id, o.offered_by, o.item_id, o.offered_item_id, o.status, i.title AS item_title, offered_item.title AS offered_item_title
          FROM offers o
          JOIN item i ON o.item_id = i.id
          JOIN item offered_item ON o.offered_item_id = offered_item.id
          WHERE o.offered_by = ?";
$stmt = mysqli_prepare($conn, $query_made);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$made_offers = mysqli_stmt_get_result($stmt);

?>
<head>
    <title>Mis Ofertas</title>
</head>
<body>
    <h1>Mis Ofertas</h1>
    <h2>Ofertas Recibidas</h2>
    <?php if (mysqli_num_rows($received_offers) > 0): ?>
        <table>
            <tr>
                <th>Artículo</th>
                <th>Ofertador</th>
                <th>Artículo Ofrecido</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php while ($offer = mysqli_fetch_assoc($received_offers)): ?>
                <?php
                    $query_user = "SELECT username FROM users WHERE id = ?";
                    $stmt_user = mysqli_prepare($conn, $query_user);
                    mysqli_stmt_bind_param($stmt_user, 'i', $offer['offered_by']);
                    mysqli_stmt_execute($stmt_user);
                    $user_result = mysqli_stmt_get_result($stmt_user);
                    $offered_by_user = mysqli_fetch_assoc($user_result);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($offer['item_title']); ?></td>
                    <td><?php echo htmlspecialchars($offered_by_user['username']); ?></td>
                    <td>
                        <a href="item.php?id=<?php echo $offer['offered_item_id']; ?>">
                            <?php echo htmlspecialchars($offer['offered_item_title']); ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($offer['status'] == 'Pendiente'): ?>
                            <span style="color: orange;">Pendiente</span>
                        <?php elseif ($offer['status'] == 'Aceptada'): ?>
                            <span style="color: green;">Aceptada</span>
                        <?php elseif ($offer['status'] == 'Rechazada'): ?>
                            <span style="color: red;">Rechazada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($offer['status'] == 'Pendiente'): ?>
                            <a href="../php/accept-offer.php?id=<?php echo $offer['offer_id']; ?>" onclick="return confirm('¿Estás seguro de que deseas aceptar esta oferta?');">Aceptar</a> |
                            <a href="../php/reject-offer.php?id=<?php echo $offer['offer_id']; ?>" onclick="return confirm('¿Estás seguro de que deseas rechazar esta oferta?');">Rechazar</a>
                        <?php else: ?>
                            No disponible
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No tienes ofertas recibidas.</p>
    <?php endif; ?>

    <h2>Mis Ofertas Realizadas</h2>
    <?php if (mysqli_num_rows($made_offers) > 0): ?>
        <table>
            <tr>
                <th>Artículo</th>
                <th>Artículo Ofrecido</th>
                <th>Estado</th>
            </tr>
            <?php while ($offer = mysqli_fetch_assoc($made_offers)): ?>
                <tr>
                    <td>
                        <a href="item.php?id=<?php echo $offer['item_id']; ?>">
                            <?php echo htmlspecialchars($offer['item_title']); ?>
                        </a>
                    </td>
                    <td>
                        <a href="item.php?id=<?php echo $offer['offered_item_id']; ?>">
                            <?php echo htmlspecialchars($offer['offered_item_title']); ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($offer['status'] == 'Pendiente'): ?>
                            <span style="color: orange;"> Esperando respuesta</span>
                        <?php elseif ($offer['status'] == 'Aceptada'): ?>
                            <span style="color: green;">Aceptada</span>
                        <?php elseif ($offer['status'] == 'Rechazada'): ?>
                            <span style="color: red;">Rechazada</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No has realizado ninguna oferta.</p>
    <?php endif; ?>
</body>
