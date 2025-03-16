<?php
session_start();
$mensaje = isset($_SESSION["mensaje"]) ? $_SESSION["mensaje"] : null;
unset($_SESSION["mensaje"]); // Borra el mensaje después de mostrarlo
?>
<?php include '../php/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>



    <?php if ($mensaje): ?>
    <div class="notificacion <?php echo htmlspecialchars($mensaje["tipo"]); ?>" id="notificacion">
        <?php echo htmlspecialchars($mensaje["texto"]); ?>
    </div>
    <?php endif; ?>

    <form id="login-form" action="../php/login-form.php" method="POST">
        Email: <input type="email" name="email" id="email" required><br>
        Contraseña: <input type="password" name="password" id="password" required><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
</body>

<?php include '../php/footer.php'; ?>
</html>

<script src="../js/notificacion.js"></script>