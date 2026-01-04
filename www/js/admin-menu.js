import { availableBadge, categoryBadge, debounce } from './common-functions.js';

let currentPage = 1;
const resultsPerPage = 4;

let cachedDishes = [];

document.getElementById("category").addEventListener("change", () => loadData(1));
document.getElementById("state").addEventListener("change", () => loadData(1));
document.getElementById("name").addEventListener("keyup", debounce(() => loadData(1), 150));


async function loadData(page = 1) {
    try {
        currentPage = page;

        const cat = document.getElementById("category").value;
        const state = document.getElementById("state").value;
        const name = document.getElementById("name").value.trim();

        const params = new URLSearchParams({
            category: cat,
            state: state,
            name,
            page: currentPage,
            per_page: resultsPerPage
        });

        const res = await fetch(`utils/api-admin-menu.php?${params.toString()}`);
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        const data = await res.json();
        cachedDishes = data.dishes;
        renderDishes(data.dishes);
        renderPagination(data.totalPages, currentPage);
    } catch (error) {
        console.error("Error fetching booking data:", error);
    }
}

function renderDishes(dishes) {
    let html = "";
    dishes.forEach(dish => {
        html += renderDish(dish);
    });
    document.getElementById("dish_list").innerHTML = html;
}

function renderDish(dish) {
    return `
        <div class="col-12 col-md-6 g-md-2">
            <div class="card shadow-sm mb-2 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${dish.name}</strong><br>
                            <small class="text-muted">${dish.calories} kCal</small>
                        </div>
                        ${availableBadge(dish.stock)}
                    </div>

                    <hr class="my-2"/>

                    <div class="d-flex justify-content-between">
                        <span>Categoria</span>
                        ${categoryBadge(dish.category_id)}
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Prezzo</span>
                        <span>€ ${dish.price}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Disponibili</span>
                        <span>${dish.stock}</span>
                    </div>

                    <div class="mb-1">
                        <span>Descrizione</span><br/>
                        <small class="text-muted">${dish.description}</small>
                    </div>

                    <hr class="my-2">

                    <div class="btn-group g-1 d-flex">
                        <button type="button" class="btn btn-outline-primary" data-id="${dish.dish_id}">
                            <i class="bi bi-pencil text-primary"></i>
                            Modifica
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderPagination(totalPages, currentPage) {
    const container = document.getElementById("pagination");
    container.innerHTML = "";

    const prev = document.createElement("button");
    prev.className = "btn btn-outline-secondary mx-1";
    prev.textContent = "<";
    prev.disabled = currentPage === 1;
    prev.addEventListener("click", () => loadData(currentPage - 1));
    container.appendChild(prev);

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.className = `btn btn-outline-primary mx-1 ${i === currentPage ? "active" : ""}`;
        btn.textContent = i;
        btn.addEventListener("click", () => loadData(i));
        container.appendChild(btn);
    }

    const next = document.createElement("button");
    next.className = "btn btn-outline-secondary mx-1";
    next.textContent = ">";
    next.disabled = currentPage === totalPages;
    next.addEventListener("click", () => loadData(currentPage + 1));
    container.appendChild(next);
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-outline-primary');
    if (!btn) return;

    const dishId = btn.dataset.id;
    const dishData = cachedDishes.find(d => d.dish_id == dishId);
    if (!dishData) return;

    // Popola il modal
    document.getElementById('modalContent').innerHTML = `
        <h5>Modifica Piatto: ${dishData.name}</h5>
        <form id="editDishForm">
            <div class="mb-2">
                <label>Nome</label>
                <input type="text" class="form-control" name="name" value="${dishData.name}">
            </div>
            <div class="mb-2">
                <label>Prezzo (€)</label>
                <input type="number" step="0.10" min="0.50" class="form-control" name="price" value="${dishData.price}">
            </div>
            <div class="mb-2">
                <label>Stock</label>
                <input type="number" class="form-control" name="stock" min="0"value="${dishData.stock}">
            </div>
            <div class="mb-2">
                <label>Categoria</label>
                <select class="form-select" name="category_id">
                    <option value="1" ${dishData.category_id == 1 ? "selected" : ""}>Primi</option>
                    <option value="2" ${dishData.category_id == 2 ? "selected" : ""}>Secondi</option>
                    <option value="3" ${dishData.category_id == 3 ? "selected" : ""}>Contorni</option>
                    <option value="4" ${dishData.category_id == 4 ? "selected" : ""}>Dolci</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Descrizione</label>
                <textarea class="form-control" name="description">${dishData.description}</textarea>
            </div>
        </form>
        <div class="d-flex justify-content-end mt-2">
            <button type="button" class="btn btn-primary" id="saveDishBtn">Salva</button>
        </div>
    `;

    // Crea l’istanza del modal
    const modalEl = document.getElementById('dishModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // Rimuove eventuali listener precedenti sul bottone Salva
    const saveBtnOld = document.getElementById('saveDishBtn');
    saveBtnOld.replaceWith(saveBtnOld.cloneNode(true));

    // Listener Salva
    document.getElementById('saveDishBtn').addEventListener('click', async () => {
        const form = document.getElementById('editDishForm');

        // Qui prendiamo i valori aggiornati dall’utente
        const formDataObj = new FormData(form);
        formDataObj.append('dish_id', dishId); // dish_id per primo non serve fare altro, basta appendarlo

        const formData = new URLSearchParams(formDataObj);
        console.log(formData.toString());
        try {
            const res = await fetch('utils/api-edit-dish.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });

            const result = await res.json();
            if (res.ok && result.success) {
                alert("Piatto aggiornato!");
                modal.hide();
                loadData(currentPage);
            } else {
                alert("Errore nell'aggiornamento del piatto");
            }
        } catch (err) {
            console.error(err);
            alert("Errore server");
        }
    });
});



renderPagination();
loadData();
