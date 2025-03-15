let locations = {};

fetch("/php/loadLocations.php")
    .then(response => response.json())
    .then(data => {
        locations = data;
        let stateSelect = document.getElementById("state");

        for (let state in locations){
            let option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            stateSelect.appendChild(option);
        }
    })
    .catch(error => console.error("Error cargando ubicaciones: ", error));

function loadCities(){
    let state = document.getElementById("state").value;
    let citySelect = document.getElementById("city");

    citySelect.innerHTML = '<option value="">Seleccione una ciudad</option>';

    if (locations[state]) {
        locations[state].forEach(city => {
            let option = document.createElement("option");
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
    }
}