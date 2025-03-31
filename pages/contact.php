<?php 
session_start();
include '../php/checkSession.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos - SwapIt</title>
    <style>
        .contact-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .contact-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .contact-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }
        .contact-title:after {
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
        .contact-form {
            display: grid;
            gap: 1.5rem;
        }
        .form-group {
            display: grid;
            gap: 0.5rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        .phone-group {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.5rem;
            align-items: start;
        }
        .phone-prefix {
            width: 100px;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
            color: #666;
        }
        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }
        .submit-btn {
            background: #0d6efd;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            justify-self: center;
        }
        .submit-btn:hover {
            background: #0b5ed7;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .contact-container {
                margin: 1rem auto;
            }
            .contact-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-content">
            <h1 class="contact-title">Contáctanos</h1>
            <form id="form-contacto" class="contact-form" onsubmit="event.preventDefault();">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" id="apellidos" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono" class="form-label">Número de Teléfono</label>
                    <div class="phone-group">
                        <select class="phone-prefix">
                            <option value="+52">+52</option>
                            <option value="+1">+1</option>
                            <option value="+54">+54</option>
                        </select>
                        <input type="tel" id="telefono" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea id="mensaje" class="form-input form-textarea" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Enviar Mensaje
                </button>
            </form>
        </div>
    </div>
    <?php include '../php/footer.php'; ?>
</body>
</html>