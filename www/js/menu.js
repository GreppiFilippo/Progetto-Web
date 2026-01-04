let timer = null;

/**
 * Performs an AJAX search and updates the menu results dynamically.
 */
function performSearch() {
    const form = document.getElementById('filterForm');
    const categoria = document.getElementById('categoria').value;
    const cerca = document.getElementById('cerca').value;
    const announceElement = document.getElementById('resultsAnnounce');
    const menuContainer = document.getElementById('menuContainer');

    const params = new URLSearchParams({
        categoria: categoria,
        cerca: cerca
    });

    fetch(`api/search-menu.php?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            updateMenuResults(data);

            // Announce results to screen readers
            let message = '';
            if (data.ricerca) {
                if (data.totale === 0) {
                    message = 'Nessun piatto trovato per la ricerca';
                } else if (data.totale === 1) {
                    message = 'Trovato 1 piatto';
                } else {
                    message = `Trovati ${data.totale} piatti`;
                }
            } else {
                if (data.totale === 1) {
                    message = '1 piatto disponibile';
                } else {
                    message = `${data.totale} piatti disponibili`;
                }
            }
            announceElement.textContent = message;
        })
        .catch(error => {
            console.error('Errore nella ricerca:', error);
            announceElement.textContent = 'Errore durante la ricerca dei piatti';
        });
}

/**
 * Updates the menu display with search results.
 * @param {Object} data - Search results data
 */
function updateMenuResults(data) {
    // Remove all existing menu sections
    const existingSections = document.querySelectorAll('main section[data-category-id]');
    existingSections.forEach(section => section.remove());

    // Remove any existing "no results" alerts
    const existingAlerts = document.querySelectorAll('main .alert.alert-info');
    existingAlerts.forEach(alert => alert.remove());

    if (data.risultati.length === 0) {
        // Show "no results" message
        const menuContainer = document.getElementById('menuContainer');
        const noResults = document.createElement('div');
        noResults.className = 'alert alert-info text-center my-4';
        noResults.innerHTML = '<i class="bi bi-info-circle me-2" aria-hidden="true"></i>Nessun piatto trovato';
        menuContainer.before(noResults);
        return;
    }

    // Render new results
    const menuContainer = document.getElementById('menuContainer');
    data.risultati.forEach(categoria => {
        const section = createCategorySection(categoria);
        menuContainer.before(section);
    });
}

/**
 * Creates a category section with dishes.
 * @param {Object} categoria - Category with dishes
 * @returns {HTMLElement} Section element
 */
function createCategorySection(categoria) {
    const section = document.createElement('section');
    section.className = 'my-5';
    section.setAttribute('data-category-id', categoria.categoria.category_id);

    const title = document.createElement('h2');
    title.className = 'h4 mb-3';
    title.textContent = categoria.categoria.category_name.charAt(0).toUpperCase() + categoria.categoria.category_name.slice(1);

    const hr = document.createElement('hr');

    const ul = document.createElement('ul');
    ul.className = 'row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 list-unstyled';
    ul.id = `menuList-${categoria.categoria.category_id}`;

    categoria.piatti.forEach(piatto => {
        const li = createDishCard(piatto);
        ul.appendChild(li);
    });

    section.appendChild(title);
    section.appendChild(hr);
    section.appendChild(ul);

    return section;
}

/**
 * Creates a dish card element.
 * @param {Object} piatto - Dish data
 * @returns {HTMLElement} List item with card
 */
function createDishCard(piatto) {
    const li = document.createElement('li');
    li.className = 'col';

    const availability = piatto.stock > 0
        ? `<span class="badge bg-success text-white"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Disponibile</span>`
        : `<span class="badge bg-danger text-white"><i class="bi bi-x-circle-fill me-1" aria-hidden="true"></i>Esaurito</span>`;

    // Generate dietary tags HTML
    let tagsHtml = '';
    if (piatto.dietary_tags && piatto.dietary_tags.length > 0) {
        tagsHtml = piatto.dietary_tags.map(tag => {
            const tagName = tag.dietary_spec_name;
            if (tagName === 'Vegano') {
                return '<span class="badge bg-success text-white p-2">Vegano</span>';
            } else if (tagName === 'Vegetariano') {
                return '<span class="badge bg-primary text-white p-2">Vegetariano</span>';
            } else if (tagName === 'Senza glutine') {
                return '<span class="badge bg-warning text-dark p-2">Senza glutine</span>';
            } else if (tagName === 'Senza lattosio') {
                return '<span class="badge bg-dark text-white p-2">Senza lattosio</span>';
            }
            return '';
        }).join(' ');
    }

    li.innerHTML = `
        <div class="card h-100 shadow-sm">
            <div class="ratio ratio-16x9">
                <img
                    src="upload/${piatto.image}"
                    class="img-fluid rounded-top"
                    alt="${escapeHtml(piatto.description)}"
                />
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h3 class="h5 mb-0 card-title">${escapeHtml(piatto.name)}</h3>
                    ${availability}
                </div>
                <p class="card-text text-muted small mb-2">
                    ${escapeHtml(piatto.description)}
                </p>
                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                    ${tagsHtml}
                    <span class="text-muted">${escapeHtml(piatto.calories)} kcal</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-basket me-1" aria-hidden="true"></i>
                        Disponibili: <strong>${escapeHtml(piatto.stock)}</strong>
                    </small>
                    <strong>€${escapeHtml(piatto.price)}</strong>
                </div>
            </div>
        </div>
    `;

    return li;
}

/**
 * Escapes HTML to prevent XSS.
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Debounce search to avoid excessive requests.
 */
function debouncedSearch() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        performSearch();
    }, 400);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function () {
    const cercaInput = document.getElementById('cerca');
    const categoriaSelect = document.getElementById('categoria');
    const form = document.getElementById('filterForm');

    // Prevent form submission if JavaScript is enabled
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            performSearch();
        });
    }

    if (cercaInput) {
        cercaInput.addEventListener('input', debouncedSearch);
    }

    if (categoriaSelect) {
        categoriaSelect.addEventListener('change', performSearch);
    }
});