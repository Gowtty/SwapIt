<?php
    include 'connectDB.php';
    
    $user_id = $_SESSION['user_id'];
    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $user['username'];
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
        .user-menu {
            min-width: 200px;
        }
        .dropdown-item {
            padding: 0.5rem 1rem;
        }
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.5rem;
        }
        .search-form {
            position: relative;
            max-width: 300px;
            width: 100%;
            margin-right: 1rem;
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
                <img src="../src/assets/SwapitLogo.ico" alt="SwapIt Logo"> SwapIt
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
                        <a class="nav-link" href="../pages/create-item.php">
                            <i class="fas fa-upload me-1"></i>Subir Artículo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/my-items.php">
                            <i class="fas fa-box me-1"></i>Mis Artículos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/my-offers.php">
                            <i class="fas fa-handshake me-1"></i>Mis Ofertas
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <form action="../pages/search.php" method="GET" class="search-form">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" class="search-input" placeholder="Buscar artículos...">
                    </form>
                    <div class="dropdown">
                        <button class="btn btn-link nav-link dropdown-toggle text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                            <li>
                                <a class="dropdown-item" href="../pages/profile.php">
                                    <i class="fas fa-user"></i>Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../pages/user-config.php">
                                    <i class="fas fa-cog"></i>Configuración
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="../php/logout.php">
                                    <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>