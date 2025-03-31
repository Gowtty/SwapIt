<?php
session_start();
include '../php/checkSession.php';
include '../php/notifications.php';

if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'profile.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SwapIt</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
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
        .input-group-text {
            border-radius: 10px 0 0 10px;
            border-right: none;
        }
        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .login-link a {
            color: #0d6efd;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        select.form-control {
            border-radius: 10px;
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
    <div class="container">
        <div class="register-container">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-user-plus text-primary me-2"></i>Crear Cuenta
                    </h2>
                    <p class="text-muted mt-2">Únete a nuestra comunidad de intercambio</p>
                </div>
                <div class="card-body">
                    <form id="register-form" action="../php/register-form.php" method="POST" onsubmit="return handleSubmit(event)">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de usuario</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       placeholder="Tu nombre de usuario"
                                       required>
                            </div>
                        </div>
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
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password_hash" 
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="conf_password" class="form-label">Confirmar contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="conf_password" 
                                       name="conf_password" 
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="tel" 
                                       class="form-control" 
                                       id="phone" 
                                       name="phone" 
                                       placeholder="(668) 856-7890"
                                       pattern="[0-9]{10}"
                                       maxlength="10"
                                       oninput="formatPhoneNumber(this)"
                                       required>
                            </div>
                            <div class="form-text text-muted">Ingresa un número de 10 dígitos sin espacios ni caracteres especiales</div>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">Estado</label>
                            <select class="form-control" id="state" name="state" required>
                                <option value="">Seleccione un estado</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="city" class="form-label">Ciudad</label>
                            <select class="form-control" id="city" name="city" required>
                                <option value="">Seleccione una ciudad</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                        </button>
                    </form>
                    <div class="login-link">
                        ¿Ya tienes una cuenta? 
                        <a href="login.php">Inicia sesión aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            input.value = value;
        }

        function handleSubmit(event) {
            event.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('conf_password').value;

            if (password !== confirmPassword) {
                showNotification('Las contraseñas no coinciden.', 'error');
                return false;
            }

            const form = document.getElementById('register-form');
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Create a temporary div to parse the HTML
                const temp = document.createElement('div');
                temp.innerHTML = html;
                
                // Find and execute any scripts in the response
                const scripts = temp.getElementsByTagName('script');
                for (let script of scripts) {
                    eval(script.innerHTML);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al registrar el usuario', 'error');
            });
            
            return false;
        }
    </script>
    <script src="../js/register.js"></script>
</body>

<?php include '../php/footer.php'; ?>