<?php 
session_start();
include '../php/checkSession.php';
?>

<head>
<title>Contáctanos</title>
</head>
<body>
<div class="container mt-5">
    <h2>Contáctanos</h2>
    <form id="form-contacto" onsubmit="event.preventDefault();">
        <div>
            <div>
                <label for="nombre" >Nombre</label>
                <input type="text" id="nombre" required>
            </div>
            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" required>
            </div>
        </div>
        <div>
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" required>
        </div>
        <div>
            <label for="telefono">Número de Teléfono</label>
            <div>
                <select style="max-width: 100px;">
                    <option value="+52">+52</option>
                    <option value="+1">+1</option>
                    <option value="+54">+54</option>
                </select>
                <input type="tel" id="telefono" required>
            </div>
        </div>
        <div>
            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" rows="4" required></textarea>
        </div>
        <button type="submit">Enviar Mensaje</button>
    </form>
</div>
<?php include '../php/footer.php'; ?>
</body>
</html>