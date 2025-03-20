<?php
session_start();

if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true) {
    // Si el usuario está logueado, muestra el header con opciones de usuario
    include('../php/header-session.php');
} else {
    // Si el usuario no está logueado, muestra el header normal
    include('../php/header.php');
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapIt</title>
</head>



<body>

    <main class="main-container">
        <div class="landing-header">
            <h2>Consigue lo que necesitas, intercambia lo que no usas</h2>
            <button class="form-button" onclick="window.location.href='../pages/register.php'">Registrate ahora</button>
        
        </div>

        <section class="divide-section"></section>
        <cite><h2>"Intercambia lo que tienes por lo que sueñas."</h2></cite>
        <div class="posts-box">
            <div class="posts-box-title">
                <h3>Últimas publicaciones</h3>
                <a href="">Ver más</a>
            </div>
            <div class="posts-cards">
                
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    
                </div>
            </div>
        </div>

        <cite><section></section></cite>

        <div class="posts-box">
            <div class="posts-box-title">
                <h3>Swaps relacionados a tus últimas búsquedas</h3>
                <a href="">Ver más</a>
            </div>
            <div class="posts-cards">
                
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    
                </div>
            </div>
        </div>

        <cite><section></section></cite>

        <div class="posts-box">
            <div class="posts-box-title">
                <h3>Swaps recomendados</h3>
                <a href="">Ver más</a>
            </div>
            <div class="posts-cards">
                
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    <a href=""><img src="" alt=""></a>
                </div>
                <div class="card">
                    
                </div>
            </div>
        </div>

        <cite><section></section></cite>
        
        <section class="divide-section"></section>

    </main>

    <?php include '../php/footer.php'; ?>
</body>