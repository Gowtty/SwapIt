<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Rechazar Oferta</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <?php
                        session_start();
                        include '../php/connectDB.php';

                        if (!isset($_SESSION['user_id'])) {
                            echo '<div class="alert alert-danger">Debes iniciar sesión para rechazar una oferta.</div>';
                            echo '<a href="../pages/login.php" class="btn btn-primary">Ir al login</a>';
                            exit;
                        }

                        $user_id = $_SESSION['user_id'];
                        $offer_id = $_GET['id'] ?? null;

                        if (!$offer_id) {
                            echo '<div class="alert alert-danger">ID de oferta no proporcionado.</div>';
                            echo '<a href="../pages/my-offers.php" class="btn btn-primary">Volver a mis ofertas</a>';
                            exit;
                        }

                        // Verificar que la oferta pertenece al usuario propietario
                        $query = "SELECT * FROM offers WHERE id = ? AND item_id IN (SELECT id FROM item WHERE user_id = ?)";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'ii', $offer_id, $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $offer = mysqli_fetch_assoc($result);

                        if (!$offer) {
                            echo '<div class="alert alert-danger">Oferta no válida.</div>';
                            echo '<a href="../pages/my-offers.php" class="btn btn-primary">Volver a mis ofertas</a>';
                            exit;
                        }

                        // Actualizar el estado de la oferta a "Rechazada"
                        $query = "UPDATE offers SET status = 'Rechazada' WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'i', $offer_id);
                        mysqli_stmt_execute($stmt);

                        echo '<script>window.location.href = "../pages/rejected-offer.php";</script>';
                        echo '<a href="../pages/my-offers.php" class="btn btn-primary">Volver a mis ofertas</a>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>