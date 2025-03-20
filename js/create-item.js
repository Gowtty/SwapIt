document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('category').addEventListener('change', function() {
        var categoryId = this.value;
        var subcategorySelect = document.getElementById('subcategory');

        // Si hay una categoría seleccionada, realizar una petición AJAX
        if (categoryId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../php/get_subcategories.php?category=' + categoryId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var subcategories = JSON.parse(xhr.responseText);
                    subcategorySelect.innerHTML = '<option value="">Seleccione una subcategoría</option>'; // Limpiar opciones anteriores

                    subcategories.forEach(function(subcategory) {
                        var option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subcategorySelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        } else {
            subcategorySelect.innerHTML = '<option value="">Seleccione una subcategoría</option>'; // Limpiar subcategorías si no hay categoría seleccionada
        }
    });
}); 