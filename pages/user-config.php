<?php
session_start();
include '../php/login-verify.php';
include '../php/connectDB.php';
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
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Configuración de Cuenta</title>
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .profile-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
        }
        .sidebar {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: #6c757d;
            border: 3px solid #0d6efd;
        }
        .sidebar-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            text-align: center;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin-bottom: 0.8rem;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 1.2rem;
            color: #666;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .sidebar-menu a i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        .sidebar-menu a:hover {
            background: #e7f1ff;
            color: #0d6efd;
            transform: translateX(5px);
        }
        .profile-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .profile-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }
        .profile-title {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        .profile-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #0d6efd;
            border-radius: 2px;
        }
        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 1px solid #ddd;
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            background: white;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            border-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }
        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .password-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
        }
        .section-title {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title i {
            color: #0d6efd;
        }
        .form-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="profile-container">
    <div class="profile-grid">
        <div class="sidebar">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2 class="sidebar-title">Mi Cuenta</h2>
            <ul class="sidebar-menu">
                <li><a href="../pages/my-items.php"><i class="fas fa-box"></i> Mis Artículos</a></li>
                <li><a href="create-item.php"><i class="fas fa-plus-circle"></i> Subir artículo</a></li>
                <li><a href="../pages/my-offers.php"><i class="fas fa-exchange-alt"></i> Mis Ofertas</a></li>
                <li><a href="../pages/user-config.php"><i class="fas fa-cog"></i> Configuración</a></li>
                <li><a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="profile-content">
            <div class="profile-header">
                <h1 class="profile-title">Configuración de Cuenta</h1>
            </div>

            <!--Alerta exitosa -->
            <div id="alerta-actualizacion" class="alert alert-success d-none" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                ¡Tus datos han sido actualizados correctamente!
            </div>

            <form id="form-configuracion" action="../php/update-config.php" method="POST">
                <!-- Información Personal -->
                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-user"></i>
                        Información Personal
                    </h5>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>

                <div class="password-section">
                    <h5 class="section-title">
                        <i class="fas fa-lock"></i>
                        Cambiar Contraseña
                    </h5>
                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label">Nueva Contraseña (opcional)</label>
                        <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena">
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena">
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../pages/profile.php'">
                        <i class="fas fa-arrow-left"></i>
                        Regresar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
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
