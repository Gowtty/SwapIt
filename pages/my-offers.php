<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/login-verify.php';

$user_id = $_SESSION['user_id'];

// Obtener todas las ofertas recibidas por los artículos del usuario
$query = "SELECT o.id AS offer_id, o.offered_by, o.item_id, o.offered_item_id, o.status, i.title AS item_title, offered_item.title AS offered_item_title
          FROM offers o
          JOIN item i ON o.item_id = i.id
          JOIN item offered_item ON o.offered_item_id = offered_item.id
          WHERE i.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$offers_result = mysqli_stmt_get_result($stmt);

?>
<h2>Ofertas Recibidas</h2>

<?php if (mysqli_num_rows($offers_result) > 0): ?>
    <table>
        <tr>
            <th>Artículo</th>
            <th>Ofertador</th>
            <th>Artículo Ofrecido</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($offer = mysqli_fetch_assoc($offers_result)): ?>
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
                <td><?php echo htmlspecialchars($offer['status']); ?></td>
                <td>
                    <?php if ($offer['status'] == 'Pendiente'): ?>
                        <a href="../php/accept-offer.php?id=<?php echo $offer['offer_id']; ?>" onclick="return confirm('¿Estás seguro de que deseas aceptar esta oferta?');">Aceptar</a> |
                        <a href="../php/reject-offer.php?id=<?php echo $offer['offer_id']; ?>" onclick="return confirm('¿Estás seguro de que deseas rechazar esta oferta?');">Rechazar</a>
                    <?php else: ?>
                        - No disponible
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No tienes ofertas pendientes.</p>
<?php endif; ?>
