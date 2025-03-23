<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contáctanos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
function mostrarMensaje() {
    const alerta = document.getElementById('mensaje-enviado');
    alerta.classList.remove('d-none');

    // Limpiar el formulario completo
    document.getElementById("form-contacto").reset();

    //Mostrar alerta 3 segundos y luego recargar
    setTimeout(() => {
        alerta.classList.add('d-none');
        window.location.reload();
    }, 3000);
}
</script>
</head>
<body>
<?php include '../php/header-session.php'; ?>
<div class="container mt-5">
    <h2>Contáctanos</h2>
    <form id="form-contacto" onsubmit="event.preventDefault(); mostrarMensaje();" class="mt-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" id="apellidos" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" id="correo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Número de Teléfono</label>
            <div class="input-group">
                <select class="form-select" style="max-width: 100px;">
                    <option value="+52">+52</option>
                    <option value="+1">+1</option>
                    <option value="+54">+54</option>
                    <!-- Aqui puedes agregar mas ladas -->
                </select>
                <input type="tel" id="telefono" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea id="mensaje" rows="4" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
    </form>

    <!-- Alerta de mensaje enviado -->
    <div id="mensaje-enviado" class="alert alert-success mt-4 d-none" role="alert">
        Gracias por tu mensaje, en unos momentos nos comunicaremos contigo.
    </div>
</div>
<?php include '../php/footer.php'; ?>
</body>
</html>
