<?php
session_start();
include '../php/connectDB.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['id'];

// Obtener datos actuales del usuario
$query = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Aquí se define $user correctamente
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración de Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../php/header-session.php'; ?>
<div class="container mt-5">
    <h2>Configuración de Cuenta</h2>
<!--Alerta exitosa -->
<div id="alerta-actualizacion" class="alert alert-success d-none" role="alert">
    ¡Tus datos han sido actualizados correctamente!
</div>

<form id="form-configuracion" action="../pages/update-config.php" method="POST" class="mt-4">
    <!-- Nombre de usuario -->
    <div class="mb-3">
        <label for="username" class="form-label">Nombre de Usuario</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>

    <!--Cambiar contraseña -->
    <div class="mb-3">
        <label for="nueva_contrasena" class="form-label">Nueva Contraseña (opcional)</label>
        <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena">
    </div>
    <div class="mb-3">
        <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
        <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena">
    </div>

    <button class="btn btn-primary" onclick="window.location.href='../pages/profile.php'">Regresar</button>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
        if (pass.length < 6) {
            e.preventDefault();
            alert("La contraseña debe tener al menos 6 caracteres.");
            return;
        }
    }

    // Mostrar alerta si hay un parámetro ?status=ok después de envío
    const url = new URL(window.location.href);
    if (url.searchParams.get("status") === "ok") {
        const alerta = document.getElementById("alerta-actualizacion");
        alerta.classList.remove("d-none");
        setTimeout(() => {
            alerta.classList.add("d-none");
            // Limpiar URL
            window.history.replaceState(null, null, window.location.pathname);
        }, 3000);
    }
});

// Ejecutar al cargar si redirigió con status=ok
window.addEventListener("DOMContentLoaded", () => {
    const url = new URL(window.location.href);
    if (url.searchParams.get("status") === "ok") {
        const alerta = document.getElementById("alerta-actualizacion");
        alerta.classList.remove("d-none");
        setTimeout(() => {
            alerta.classList.add("d-none");
            window.history.replaceState(null, null, window.location.pathname);
        }, 3000);
    }
});
</script>
</body>
</html>
