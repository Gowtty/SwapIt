<?php
session_start();
include '../php/checkSession.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de Nosotros - SwapIt</title>
    <style>
        .about-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .about-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .about-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }
        .about-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: #0d6efd;
            border-radius: 2px;
        }
        .about-text {
            color: #666;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        .about-text:last-child {
            margin-bottom: 0;
        }
        .about-highlight {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            border-left: 4px solid #0d6efd;
        }
        .about-highlight p {
            margin: 0;
            color: #333;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .about-container {
                margin: 1rem auto;
            }
            .about-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="about-container">
        <div class="about-content">
            <h1 class="about-title">Acerca de Nosotros</h1>
            <p class="about-text">Somos una plataforma de trueques online creada para conectar a personas que desean intercambiar bienes y servicios de manera fácil, segura y sostenible. Nuestra misión es fomentar una comunidad donde el valor no se mida en dinero, sino en la utilidad que cada objeto o habilidad puede tener para alguien más.</p>
            
            <div class="about-highlight">
                <p>En un mundo donde la economía colaborativa y el consumo responsable son cada vez más importantes, nos enfocamos en promover el intercambio como una alternativa inteligente y ecológica.</p>
            </div>
            
            <p class="about-text">Creemos que lo que ya no usas puede ser el tesoro de otra persona, y viceversa. Nuestra plataforma está diseñada para ser intuitiva, accesible y confiable, permitiéndote encontrar lo que necesitas y deshacerte de lo que ya no usas, todo en un mismo lugar.</p>
            
            <p class="about-text">Ya sea un libro, una prenda de ropa, un mueble o un servicio, aquí encontrarás oportunidades para intercambiar sin complicaciones. En SwapIt, no solo facilitamos trueques, sino que también construimos una comunidad comprometida con la reutilización, el ahorro y la conexión entre personas.</p>
            
            <div class="about-highlight">
                <p>¡Únete a nosotros y descubre una nueva forma de obtener lo que necesitas!</p>
            </div>
        </div>
    </div>
    <?php include '../php/footer.php'; ?>
</body>
</html>