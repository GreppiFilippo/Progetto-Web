import { availableBadge, categoryBadge, debounce, renderPagination } from './common-functions.js';

document.getElementById("add-dish").addEventListener("click", () => {
    window.location.href = "admin-add-dish.php";
});

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

        const res = await fetch(`api/admin-menu.php?${params.toString()}`);
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        const data = await res.json();
        cachedDishes = data.dishes;
        renderDishes(data.dishes);
        renderPagination(data.totalPages, currentPage, loadData);
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
                        <button type="button" class="btn btn-outline-primary admin-btn" data-id="${dish.dish_id}">
                            <i class="bi bi-pencil"></i>
                            Modifica
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

document.addEventListener('click', (e) => {
    // cerca il bottone più vicino con la classe btn-outline-primary
    const btn = e.target.closest('.btn-outline-primary');
    if (!btn) return;

    // prendi l'id del piatto
    const dishId = btn.dataset.id;
    if (!dishId) return;
    // reindirizza a admin-edit-dish.php?id=<dishId>
    window.location.href = `admin-edit-dish.php?id=${dishId}`;
});


loadData();