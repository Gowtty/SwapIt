<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';

// Obtener las últimas 5 publicaciones de la base de datos
$query = "SELECT id, title, images FROM item ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conn, $query);
$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                
                <?php foreach ($items as $item): 
                    $images = json_decode($item['images'], true);
                    $firstImage = $images[0] ?? '../src/default.jpg';
                ?>
                <div class="card">
                    <a href="item.php?id=<?php echo $item['id']; ?>">
                        <img src="<?php echo htmlspecialchars($firstImage); ?>" 
                        alt="<?php echo htmlspecialchars($item['title']); ?>" 
                        />
                    </a>
                </div>
                <?php endforeach; ?>
                    
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