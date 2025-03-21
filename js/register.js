window.onload = function () {
    let locations = {};

    // Cargar estados desde PHP
    fetch("../php/loadLocations.php")
        .then(response => response.json())
        .then(data => {
            locations = data;
            let stateSelect = document.getElementById("state");

            for (let state in locations) {
                let option = document.createElement("option");
                option.value = state;
                option.textContent = state;
                stateSelect.appendChild(option);
            }
        })
        .catch(error => console.error("Error cargando ubicaciones: ", error));

    // Cargar ciudades al seleccionar un estado
    window.loadCities = function () {
        let state = document.getElementById("state").value;
        let citySelect = document.getElementById("city");
        let postalSelect = document.getElementById("postal_code");

        citySelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
        postalSelect.innerHTML = '<option value="">Seleccione un código postal</option>';

        if (state in locations && Array.isArray(locations[state])) {
            const addedCities = new Set();

            locations[state].forEach(entry => {
                if (!addedCities.has(entry.city)) {
                    let option = document.createElement("option");
                    option.value = entry.city;
                    option.textContent = entry.city;
                    citySelect.appendChild(option);
                    addedCities.add(entry.city);
                    console.log("DEBUG:", entry);
                }
            });
        }
    };

    // Cargar códigos postales al seleccionar una ciudad
    document.getElementById("city").addEventListener("change", function () {
        let state = document.getElementById("state").value;
        let city = this.value;
        let postalSelect = document.getElementById("postal_code");

        postalSelect.innerHTML = '<option value="">Seleccione un código postal</option>';

        if (state in locations) {
            locations[state].forEach(entry => {
                if (entry.city.trim().toLowerCase() === city.trim().toLowerCase()) {
                    console.log(">> Código Postal Encontrado:", entry.postal_code);
                    let option = document.createElement("option");
                    option.value = entry.postal_code;
                    option.textContent = entry.postal_code;
                    postalSelect.appendChild(option);
                }
            });
        }
    });

    // Validación de contraseña
    document.getElementById('register-form').addEventListener('submit', function (event) {
        if (!validatePassword()) {
            event.preventDefault();
        }
    });

    function validatePassword() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('conf_password').value;

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return false;
        }
        return true;
    }
};
