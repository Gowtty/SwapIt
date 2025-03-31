<?php
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/checkSession.php';

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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Ofertas</title>
    <style>
        .offer-status {
            font-weight: 500;
        }
        .offer-status.pending { color: #ffc107; }
        .offer-status.accepted { color: #198754; }
        .offer-status.rejected { color: #dc3545; }
        .table-responsive {
            margin-top: 1rem;
        }
        .table {
            width: 100%;
            margin-bottom: 0;
        }
        .table th {
            white-space: nowrap;
            background-color: #f8f9fa;
        }
        .table td {
            vertical-align: middle;
        }
        .btn-sm {
            margin: 0 2px;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-5">
        <div class="container">
            <h1 class="mb-4">Mis Ofertas</h1>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Ofertas Recibidas</h2>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($received_offers) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Artículo</th>
                                        <th>Ofertador</th>
                                        <th>Artículo Ofrecido</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                                <a href="item.php?id=<?php echo $offer['offered_item_id']; ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($offer['offered_item_title']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($offer['status'] == 'Pendiente'): ?>
                                                    <span class="offer-status pending">Pendiente</span>
                                                <?php elseif ($offer['status'] == 'Aceptada'): ?>
                                                    <span class="offer-status accepted">Aceptada</span>
                                                <?php elseif ($offer['status'] == 'Rechazada'): ?>
                                                    <span class="offer-status rejected">Rechazada</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($offer['status'] == 'Pendiente'): ?>
                                                    <a href="../php/accept-offer.php?id=<?php echo $offer['offer_id']; ?>" 
                                                       onclick="return confirm('¿Estás seguro de que deseas aceptar esta oferta?');"
                                                       class="btn btn-success btn-sm">Aceptar</a>
                                                    <a href="../php/reject-offer.php?id=<?php echo $offer['offer_id']; ?>" 
                                                       onclick="return confirm('¿Estás seguro de que deseas rechazar esta oferta?');"
                                                       class="btn btn-danger btn-sm">Rechazar</a>
                                                <?php else: ?>
                                                    <span class="text-muted">No disponible</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-3">
                            <p class="text-muted mb-0">No tienes ofertas recibidas.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Mis Ofertas Realizadas</h2>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($made_offers) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Artículo</th>
                                        <th>Artículo Ofrecido</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($offer = mysqli_fetch_assoc($made_offers)): ?>
                                        <tr>
                                            <td>
                                                <a href="item.php?id=<?php echo $offer['item_id']; ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($offer['item_title']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="item.php?id=<?php echo $offer['offered_item_id']; ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($offer['offered_item_title']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($offer['status'] == 'Pendiente'): ?>
                                                    <span class="offer-status pending">Esperando respuesta</span>
                                                <?php elseif ($offer['status'] == 'Aceptada'): ?>
                                                    <span class="offer-status accepted">Aceptada</span>
                                                <?php elseif ($offer['status'] == 'Rechazada'): ?>
                                                    <span class="offer-status rejected">Rechazada</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-3">
                            <p class="text-muted mb-0">No has realizado ninguna oferta.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include '../php/footer.php'; ?>
</body>
</html>
