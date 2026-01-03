export function isToday(dateTime) {
    const today = new Date();          // data odierna
    const date = new Date(dateTime);   // data da controllare

    return date.getDate() === today.getDate() &&
           date.getMonth() === today.getMonth() &&
           date.getFullYear() === today.getFullYear();
}

export function isTomorrow(dateTime) {
    const today = new Date();          // data odierna
    const date = new Date(dateTime);   // data da controllare

    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    return date.getDate() === tomorrow.getDate() &&
           date.getMonth() === tomorrow.getMonth() &&
           date.getFullYear() === tomorrow.getFullYear();
}

export function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

export function availableBadge(stock) {
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

export function categoryBadge(category) {
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