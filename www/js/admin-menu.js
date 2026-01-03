const debouncedData = debounce(loadData, 300);

document.getElementById("category").addEventListener("change", loadData);
document.getElementById("state").addEventListener("change", loadData);
document.getElementById("name").addEventListener("keyup", debouncedData);

async function loadData() {
    try {
        const cat = document.getElementById("category");
        const state = document.getElementById("state");
        const name = document.getElementById("name").value.trim();

        const params = new URLSearchParams({
            category: cat.value,
            state: state.value,
            name
        });


        const res = await fetch(`utils/api-admin-menu.php?${params.toString()}`);
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        const data = await res.json();
        renderDishes(data);
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

                    <hr class="my-2">

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

                </div>
            </div>
        </div>
    `;
}


function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

function availableBadge(stock) {
    stock = parseInt(stock, 10);
    if (stock > 10) {
        return `
            <span class="badge bg-success text-white p-2">
                <i class="bi bi-check-circle me-1"></i>
                Disponibile
            </span>
        `;
    } else if (stock > 0) {
        return `
            <span class="badge bg-warning text-dark p-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Disponibilità limitata
            </span>
        `;
    } else {
        return `
            <span class="badge bg-danger text-white p-2">
                <i class="bi bi-x-circle me-1"></i>
                Non disponibile
            </span>
        `;
    }
}

function categoryBadge(category) {
    if (category == 1) {
        return `
            <span class="badge bg-warning text-dark p-2">
                Primi
            </span>
        `;
    } else if (category == 2) {
        return `
            <span class="badge bg-info text-white p-2">
                Secondi
            </span>
        `;
    } else if (category == 3) {
        return `
            <span class="badge bg-success text-white p-2">
                Contorni
            </span>
        `;
    } else if (category == 4) {
        return `
            <span class="badge bg-danger text-white p-2">
                Dolci
            </span>
        `;
    }
}


loadData();
