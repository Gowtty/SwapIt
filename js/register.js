let locations = {};


fetch("../php/loadLocations.php")
    .then(response => response.json())// Se lee la respuesta json de php
    .then(data => {
        locations = data;
        // Se crea una variable que hace referencia al state del form y se crean las opciones de cada estado encontrado en el json
        let stateSelect = document.getElementById("state");

        for (let state in locations){
            let option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            stateSelect.appendChild(option);
        }
    })
    .catch(error => console.error("Error cargando ubicaciones: ", error));
// funcion al ejecutar un cambio en el select de estado del form, para seleccionar las ciudades disponibles en ese estado
function loadCities(){
    let state = document.getElementById("state").value;
    let citySelect = document.getElementById("city");

    citySelect.innerHTML = '<option value="">Seleccione una ciudad</option>';

    if (state in locations && Array.isArray(locations[state])) {//verificar el estado y agregar opciones de las ciudades correspondientes a ese estado
        locations[state].forEach(city => {
            let option = document.createElement("option");
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
    }
}

// Asignar la validación al formulario
document.getElementById('register-form').addEventListener('submit', function(event) {
    if (!validatePassword()) {
        event.preventDefault(); // Prevenir el envío del formulario si la validación falla
    }
});

// Función para validar la contraseña
function validatePassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('conf_password').value;
    
    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden.');
        return false; // Impide el envío del formulario
    }
    return true; // Permite el envío del formulario si las contraseñas coinciden
}




