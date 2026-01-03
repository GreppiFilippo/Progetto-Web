import { availableBadge, categoryBadge, debounce } from './common-functions.js';

let currentPage = 1;
const resultsPerPage = 4;

const debouncedData = debounce(loadData, 150);

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
        <div class="col-12 col-md-4">
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
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-pencil text-primary"></i>
                            Modifica
                        </button>
                        <button type="button" class="btn btn-outline-danger ms-1">
                            <i class="bi bi-trash text-danger"></i>
                            Elimina
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

renderPagination();
loadData();
