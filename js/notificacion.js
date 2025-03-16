window.onload = function() {
    var notificacion = document.getElementById("notificacion");
    if (notificacion) {
        notificacion.style.display = "block";
        setTimeout(function() {
            notificacion.style.display = "none";
        }, 3000); // Se oculta después de 3 segundos
    }
};