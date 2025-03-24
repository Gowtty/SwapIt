<?php
session_start();
include '../php/connectDB.php';
include '../php/login-verify.php';
include '../php/checkSession.php';

$usuario_id = $_SESSION['user_id'];

// Obtener datos actuales del usuario
$query = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<head>
    <title>Configuración de Cuenta</title>
</head>
<body>
<div>
    <h2>Configuración de Cuenta</h2>

<form id="form-configuracion" action="../php/update-config.php" method="POST">
    <div>
        <label for="username">Nombre de Usuario</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>

    <div>
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>

    <div>
        <label for="nueva_contrasena">Nueva Contraseña (opcional)</label>
        <input type="password" id="nueva_contrasena" name="nueva_contrasena">
    </div>
    <div>
        <label for="confirmar_contrasena">Confirmar Nueva Contraseña</label>
        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena">
    </div>

    <button type="button" onclick="window.location.href='../pages/profile.php'">Regresar</button>

    <button type="submit">Guardar Cambios</button>
</form>
</div>
<?php include '../php/footer.php'; ?>

<script>
document.getElementById("form-configuracion").addEventListener("submit", function (e) {
    const pass = document.getElementById("nueva_contrasena").value;
    const confirm = document.getElementById("confirmar_contrasena").value;

    // Validar coincidencia de contraseñas si hay intento de cambio
    if (pass || confirm) {
        if (pass !== confirm) {
            e.preventDefault();
            alert("Las contraseñas no coinciden.");
            return;
        }
        if (pass.length < 8) {
            e.preventDefault();
            alert("La contraseña debe tener al menos 8 caracteres.");
            return;
        }
    }
});
</script>
</body>
</html>