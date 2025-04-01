<?php
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/checkSession.php';

// Get the offer ID from the URL
$offer_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$offer_id) {
    echo '<script>window.location.href = "my-offers.php";</script>';
    exit;
}

// First verify that this offer belongs to the current user's item and is accepted
$verify_query = "SELECT o.* FROM offers o 
                 JOIN item i ON o.item_id = i.id 
                 WHERE o.id = ? AND i.user_id = ? AND o.status = 'Aceptada'";
$verify_stmt = mysqli_prepare($conn, $verify_query);
mysqli_stmt_bind_param($verify_stmt, 'ii', $offer_id, $user_id);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);

if (mysqli_num_rows($verify_result) === 0) {
    echo '<script>window.location.href = "my-offers.php";</script>';
    exit;
}

mysqli_stmt_close($verify_stmt);

// Get the offer details
$query = "SELECT o.*, u.email, u.phone, u.username, u.city, u.state, i.title as item_title
          FROM offers o 
          JOIN users u ON o.offered_by = u.id 
          JOIN item i ON o.item_id = i.id
          WHERE o.id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $offer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$offer = mysqli_fetch_assoc($result);

if (!$offer) {
    echo '<script>window.location.href = "my-offers.php";</script>';
    exit;
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta Aceptada - SwapIt</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">¡Oferta Aceptada!</h4>
                            <p>Has aceptado la oferta de <?php echo htmlspecialchars($offer['username']); ?> para el artículo "<?php echo htmlspecialchars($offer['item_title']); ?>".</p>
                            <p>Muchas gracias por utilizar SwapIt, esperamos que disfrutes de tu intercambio.</p>
                            <hr>
                            <p class="mb-0">Aquí tienes la información de contacto para coordinar el intercambio:</p>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Información de Contacto</h5>
                                <p class="card-text">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($offer['email']); ?><br>
                                    <strong>Teléfono:</strong> <?php echo htmlspecialchars($offer['phone']); ?><br>
                                    <strong>Ubicación:</strong> <?php echo htmlspecialchars($offer['city'] . ', ' . $offer['state']); ?>
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <p class="mb-0">Recuerda coordinar el intercambio de manera segura y en un lugar público.</p>
                        </div>

                        <a href="../pages/my-offers.php" class="btn btn-primary">Volver a mis ofertas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
