<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/x-icon" href="../src/assets/SwapitLogo.ico">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        
    <a href="../pages/index.php"><img src="../src/assets/SwapitLogo.ico" alt="SwapIt Logo"></a>
    <nav class="nav-links">
        <ul>
            <a href="../pages/index.php"><li>Inicio</li></a>
            <a href="../pages/create-item.php"><li>Intercambio</li></a>
            <a href="../pages/aboutus.php"><li>Acerca de</li></a>
            <a href="../pages/contact.php"><li>Contáctanos</li></a>
        </ul>
    </nav>

    <input type="search" placeholder="Buscar articulo">
    <div class="nav-buttons">
        <button class="form-button" onclick="window.location.href='../pages/profile.php'">
        <i class="fa-solid fa-user"></i> Mi Perfil
        </button>
        <button class="form-button" onclick="window.location.href='../php/logout.php'">Cerrar sesión</button>
    </div>

    </header>
<body>