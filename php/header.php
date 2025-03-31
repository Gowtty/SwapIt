<?php
include 'connectDB.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../src/assets/SwapitLogo.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar-brand img {
            width: 35px;
            height: 35px;
            object-fit: contain;
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
            width: 11vw;
        }

        .btn-outline-primary {
            width: 11vw;
        }
        .search-form {
            position: relative;
            max-width: 400px;
            width: 100%;
        }
        .search-input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .search-input:focus {
            outline: none;
            border-color: #0d6efd;
            background: white;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            color: #333;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.5rem;
        }
        @media (max-width: 768px) {
            .search-form {
                max-width: 100%;
                margin: 1rem 0;
            }
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
                <div class="d-flex gap-2 align-items-center">
                    <form action="../pages/search.php" method="GET" class="search-form">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" class="search-input" placeholder="Buscar artículos...">
                    </form>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="../pages/profile.php"><i class="fas fa-user-circle"></i>Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="../pages/my-items.php"><i class="fas fa-box"></i>Mis Artículos</a></li>
                                <li><a class="dropdown-item" href="../pages/my-offers.php"><i class="fas fa-exchange-alt"></i>Mis Ofertas</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../php/logout.php"><i class="fas fa-sign-out-alt"></i>Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="../pages/login.php" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                        </a>
                        <a href="../pages/register.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Registrarse
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>