document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("login-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Evita la recarga

        let formData = new FormData(this);

        fetch("../php/login-form.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let notificacion = document.getElementById("notificacion");

            notificacion.className = `notificacion ${data.tipo}`;
            notificacion.innerHTML = data.texto;
            notificacion.style.display = "block";

            setTimeout(function(){
                notificacion.style.display = "none";
            }, 2000);

            // Si el login es exitoso, redirigir después de 1 segundo
            if (data.tipo === "success") {
                setTimeout(() => {
                    window.location.href = "/pages/profile.php";
                }, 1000);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});