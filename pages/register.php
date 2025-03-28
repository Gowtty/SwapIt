<?php include '../php/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>

    <form action = "../php/register-form.php" id = "register-form" method="POST">
        Nombre de usuario: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Contraseña: <input id="password" type="password" name="password_hash" required><br>
        Confirmar contraseña: <input id="conf_password" type="password" name="conf_password" required><br>
        Teléfono: <input type="tel" name="phone" required><br>
        Estado:
        <select id="state" name="state" onchange="loadCities()">
            <option value="">Seleccione un estado</option>
        </select><br>

        Ciudad:
        <select id="city" name="city">
            <option value="">Seleccione una ciudad</option>
        </select><br>
        
        <input type="submit" value="Crear cuenta">

    </form>
</body>

<?php include '../php/footer.php'; ?>
<script src="../js/register.js"></script>