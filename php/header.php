<?php
include 'connectDB.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../src/assets/SwapitLogo.ico">
    <title>SwapIt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        .nav-link:hover {
            color: #0d6efd !important;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .btn-primary {
            padding: 0.5rem 1.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="../pages/index.php">
                <img src="../src/assets/SwapitLogo.ico" alt="SwapIt Logo" width="30" height="30"> SwapIt
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/index.php">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/aboutus.php">
                            <i class="fas fa-info-circle me-1"></i>Acerca de
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/contact.php">
                            <i class="fas fa-envelope me-1"></i>Contáctanos
                        </a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="../pages/login.php" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                    </a>
                    <a href="../pages/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>