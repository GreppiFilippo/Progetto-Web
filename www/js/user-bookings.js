/**
 * CartView to manage cart UI state (frontend only).
 */
class CartView {
    constructor() {
        /** @type {Map<number, {name: string, price: number, quantity: number}>} */
        this.items = new Map();
    }

    /**
     * Set item in cart.
     * 
     * @param {number} dishId The ID of the dish
     * @param {string} dishName The name of the dish
     * @param {number} price The price of the dish
     * @param {number} quantity The quantity of the dish
     * @returns {void}
     */
    setItem(dishId, dishName, price, quantity) {
        if (
            typeof dishId !== 'number' ||
            typeof dishName !== 'string' ||
            typeof price !== 'number' ||
            typeof quantity !== 'number'
        ) {
            return;
        }

        if (quantity <= 0) {
            this.items.delete(dishId);
            return;
        }

        this.items.set(dishId, {
            name: dishName,
            price,
            quantity
        });
    }

    /**
     * Get item from cart.
     * 
     * @param {number} dishId The ID of the dish
     * @returns {{name: string, price: number, quantity: number}|undefined} The item data or undefined if not found
     */
    getItem(dishId) {
        return this.items.get(dishId);
    }

    /**
     * Get all items in cart.
     * 
     * @returns {Array<{dishId: number, name: string, price: number, quantity: number}>} The list of all items
     */
    getAllItems() {
        return Array.from(this.items.entries()).map(([dishId, data]) => ({
            dishId,
            ...data
        }));
    }

    /**
     * Calculate total price of items in cart.
     * 
     * @returns {number} The total price
     */
    calculateTotal() {
        let total = 0;
        for (const item of this.items.values()) {
            total += item.price * item.quantity;
        }
        return total;
    }

    /**
     * Check if cart is empty.
     * 
     * @returns {boolean} True if cart is empty, false otherwise
     */
    isEmpty() {
        return this.items.size === 0;
    }
}

const cart = new CartView();

/**
 * Setup date input change handler.
 * 
 * @returns {void}
 */
function setupDateTimeHandler() {
    const dateInput = document.getElementById('booking-date');
    const timeSelect = document.getElementById('booking-time');

    if (!dateInput || !timeSelect) return;

    timeSelect.disabled = true;

    dateInput.addEventListener('change', () => {
        const selectedDate = dateInput.value;

        if (!selectedDate) {
            resetTimeSelect(timeSelect, 'Seleziona prima la data');
            return;
        }

        fetch(`utils/get-time-slots.php?date=${encodeURIComponent(selectedDate)}`)
            .then(r => {
                if (!r.ok) throw new Error('Server error');
                return r.json();
            })
            .then(data => populateTimeSlots(timeSelect, data.slots))
            .catch(() => {
                resetTimeSelect(timeSelect, 'Errore nel caricamento');
            });
    });
}

/**
 * Populate time slots in select element.
 * 
 * @param {HTMLSelectElement} select The select element to populate
 * @param {Array<{value: string, label: string}>} slots The time slots to populate
 * @returns {void}
 */
function populateTimeSlots(select, slots = []) {
    select.innerHTML = '<option value="" disabled selected>Seleziona orario</option>';

    if (!Array.isArray(slots) || slots.length === 0) {
        resetTimeSelect(select, 'Nessun orario disponibile');
        return;
    }

    slots.forEach(slot => {
        const option = document.createElement('option');
        option.value = slot.value;
        option.textContent = slot.label;
        select.appendChild(option);
    });

    select.disabled = false;
}

/**
 * Reset time select element.
 * 
 * @param {HTMLSelectElement} select The select element to reset
 * @param {string} message The message to display
 */
function resetTimeSelect(select, message) {
    select.innerHTML = `<option value="" disabled selected>${message}</option>`;
    select.disabled = true;
}

/**
 * Setup quantity input handler.
 * 
 * @returns {void}
 */
function setupQuantityHandler() {
    const form = document.querySelector('form[action="user-bookings.php"]');
    if (!form) return;

    form.addEventListener('input', e => {
        if (e.target.classList.contains('dish-quantity-input')) {
            updateSummary(e.target);
        }
    });
}

/**
 * Update summary when quantity input changes.
 * 
 * @param {HTMLInputElement} input 
 * @returns {void}
 */
function updateSummary(input) {
    const dishId = parseInt(input.dataset.dishId, 10);
    const dishName = input.dataset.dishName;
    const price = parseFloat(input.dataset.price);
    const quantity = parseInt(input.value, 10) || 0;

    if (Number.isNaN(dishId) || Number.isNaN(price)) return;

    cart.setItem(dishId, dishName, price, quantity);
    renderSummary();
}

/**
 * Render the summary table.
 * 
 * @returns {void}
 */
function renderSummary() {
    const tbody = document.getElementById('riepilogo-table-body');
    const wrapper = document.getElementById('riepilogo-table-wrapper');
    const placeholder = document.getElementById('riepilogo-placeholder');
    const totalElement = document.getElementById('riepilogo-total-value');

    if (!tbody) {
        return;
    }

    tbody.innerHTML = '';

    if (cart.isEmpty()) {
        wrapper?.classList.add('d-none');
        placeholder?.classList.remove('d-none');
        totalElement?.classList.add('d-none');
        return;
    }

    wrapper?.classList.remove('d-none');
    placeholder?.classList.add('d-none');

    cart.getAllItems().forEach(item => {
        tbody.appendChild(createSummaryRow(item));
    });

    renderTotal();
}

/**
 * Create a summary table row for an item.
 * 
 * @param {*} item The cart item
 * @returns {HTMLTableRowElement} The table row element
 */
function createSummaryRow(item) {
    const tr = document.createElement('tr');
    const subtotal = item.price * item.quantity;

    tr.innerHTML = `
        <td class="align-middle">${escapeHtml(item.name)}</td>
        <td class="text-end align-middle">${item.quantity}</td>
        <td class="text-end align-middle">${formatEuro(item.price)}</td>
        <td class="text-end align-middle">${formatEuro(subtotal)}</td>
    `;

    return tr;
}

/**
 * Render total price in summary.
 * 
 * @returns {void}
 */
function renderTotal() {
    const totalElement = document.getElementById('riepilogo-total-value');
    if (!totalElement) return;

    const total = cart.calculateTotal();
    totalElement.classList.remove('d-none');

    const valueSpan = totalElement.querySelector('span:last-child');
    if (valueSpan) {
        valueSpan.textContent = formatEuro(total);
    }
}

// ------ Utility functions ------

/**
 * Format a number as Euro currency.
 * 
 * @param {number} n The number to format
 * @returns {string} The formatted string
 */
function formatEuro(n) {
    return '€' + n.toFixed(2).replace('.', ',');
}

/**
 * Escape HTML special characters in a string.
 * 
 * @param {string} str The string to escape
 * @returns {string} The escaped string
 */
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

document.addEventListener('DOMContentLoaded', () => {
    setupDateTimeHandler();
    setupQuantityHandler();
});