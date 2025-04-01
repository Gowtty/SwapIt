<?php
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
include '../php/checkSession.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta Rechazada - SwapIt</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">¡Oferta Rechazada!</h4>
                            <p>Has rechazado la oferta exitosamente.</p>
                            <hr>
                            <p class="mb-0">La otra parte ha sido notificada de tu decisión.</p>
                        </div>
                        <a href="../pages/my-offers.php" class="btn btn-primary">Volver a mis ofertas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>











