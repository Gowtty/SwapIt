<?php 
session_start();
include '../php/checkSession.php';
if (isset($_SESSION['user_id'])) {
    header("Location: ../pages/profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SwapIt</title>
    <?php include '../php/notifications.php'; ?>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 2rem auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
            padding: 2rem 1.5rem 1rem;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .notificacion {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: white;
            z-index: 1000;
            display: none;
            animation: slideIn 0.3s ease-out;
        }
        .notificacion.success {
            background-color: #198754;
        }
        .notificacion.error {
            background-color: #dc3545;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .register-link a {
            color: #0d6efd;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['notification'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('" . htmlspecialchars($_SESSION['notification']['message']) . "', '" . $_SESSION['notification']['type'] . "');
            });
        </script>";
        unset($_SESSION['notification']);
    }
    ?>

    <div id="notificacion" class="notificacion"></div>

    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>SwapIt
                    </h2>
                    <p class="text-muted mt-2">Inicia sesión para continuar</p>
                </div>
                <div class="card-body">
                    <form id="login-form" action="../php/login-form.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="ejemplo@correo.com"
                                       required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </form>
                    <div class="register-link">
                        ¿No tienes una cuenta? 
                        <a href="register.php">Regístrate aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/login.js"></script>
</body>

<?php include '../php/footer.php'; ?>