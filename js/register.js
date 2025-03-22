let locations = {};

window.onload = function () {
    let stateSelect = document.getElementById("state");
    let citySelect = document.getElementById("city");
    // Cargar estados desde PHP
    fetch("../php/loadLocations.php")
        .then(response => response.json())
        .then(data => {
            locations = data;
            

            for (let state in locations) {
                let option = document.createElement("option");
                option.value = state;
                option.textContent = state;
                stateSelect.appendChild(option);
            }
        })
        .catch(error => console.error("Error cargando ubicaciones: ", error));

    // Cargar ciudades al seleccionar un estado
    stateSelect.addEventListener("change", function () {
        let state = stateSelect.value;
        
        citySelect.innerHTML = '<option value="">Seleccione una ciudad</option>';

        if (locations[state] && Array.isArray(locations[state])) {
            const addedCities = new Set();

            locations[state].forEach(entry => {
                if (entry.city && !addedCities.has(entry.city)) {
                    let option = document.createElement("option");
                    option.value = entry.city;
                    option.textContent = entry.city;
                    citySelect.appendChild(option);
                    addedCities.add(entry.city);
                }
            });
        } else {
            console.warn(`No se encontraron ciudades para el estado: ${state}`);
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
