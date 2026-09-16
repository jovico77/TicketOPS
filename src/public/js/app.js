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

const editCategory = document.getElementById('edit-category');
const editSubcategory = document.getElementById('edit-subcategory');

if (editCategory && editSubcategory) {
    editCategory.addEventListener('change', function () {
        const selectedCategory = this.value;

        editSubcategory.innerHTML = '';

        if (!selectedCategory) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Select a category first';
            editSubcategory.appendChild(option);
            editSubcategory.disabled = true;
            return;
        }

        editSubcategory.disabled = true;

        fetch(`/categories/${selectedCategory}/subcategories`)
            .then(response => response.json())
            .then(data => {
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select a subcategory';
                editSubcategory.appendChild(defaultOption);

                data.forEach(function (item) {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    editSubcategory.appendChild(option);
                });

                editSubcategory.disabled = false;
            })
            .catch(error => {
                console.error('Error loading edit subcategories:', error);
                editSubcategory.disabled = false;
            });
    });
}

// Modal de edición de tickets
document.querySelectorAll('[data-modal-open]').forEach(function (openButton) {
    openButton.addEventListener('click', function () {
        const modal = document.getElementById(this.dataset.modalOpen);

        if (modal) {
            modal.classList.add('is-visible');
        }
    });
});

document.querySelectorAll('[data-modal-close]').forEach(function (closeButton) {
    closeButton.addEventListener('click', function () {
        const modal = this.closest('[data-modal]');

        if (modal) {
            modal.classList.remove('is-visible');
        }
    });
});

document.querySelectorAll('[data-modal]').forEach(function (modal) {
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.classList.remove('is-visible');
        }
    });
});

if (document.querySelector('[data-modal] .is-invalid')) {
    document.querySelector('[data-modal]').classList.add('is-visible');
}