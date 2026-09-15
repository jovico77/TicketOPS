// Recoger categoría y subcategoría
const category = document.getElementById('category');
const subcategory = document.getElementById('subcategory');

// Solo agregar el event listener si ambos elementos existen
if (category && subcategory) {
    // Escuchar cambios en la categoría
    category.addEventListener('change', function () {
        const selectedCategory = this.value;

        subcategory.innerHTML = '';

        // Si no hay categoría seleccionada, deshabilitar el subcategory y mostrar un mensaje
        if (!selectedCategory) {
            subcategory.disabled = true;

            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Select a category first';

            subcategory.appendChild(option);

            return;
        }

        // Deshabilitar el subcategory mientras se cargan los datos
        subcategory.disabled = true;

        const loadingOption = document.createElement('option');
        loadingOption.value = '';
        loadingOption.textContent = 'Loading...';

        subcategory.appendChild(loadingOption);

        // Hacer la solicitud fetch para obtener las subcategorías (AJAX)
        fetch(`/categories/${selectedCategory}/subcategories`)
            .then(response => response.json())
            .then(data => {
                subcategory.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select a subcategory';

                subcategory.appendChild(defaultOption);

                data.forEach(function (item) {
                    const option = document.createElement('option');

                    option.value = item.id;
                    option.textContent = item.name;

                    subcategory.appendChild(option);
                });

                subcategory.disabled = false;
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);

                subcategory.innerHTML = '';

                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Error loading subcategories';

                subcategory.appendChild(option);
            });
    });
}