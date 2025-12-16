//TODO: fix this file


function aggiornaRiepilogo(dishName, dishId, quantity) {
    // Accept both signatures:
    // - aggiornaRiepilogo(dishName, dishId, quantity)
    // - legacy: aggiornaRiepilogo(dishId, quantity)
    if (quantity === undefined && dishId !== undefined) {
        // called with (dishId, quantity)
        quantity = dishId;
        dishId = dishName;
        dishName = null;
    }

    const itemsWrapper = document.getElementById('riepilogo-table-wrapper');
    const tbody = document.getElementById('riepilogo-table-body');
    const placeholder = document.getElementById('riepilogo-placeholder');
    const hr = document.getElementById('riepilogo-hr');
    if (!tbody || !itemsWrapper) {
        console.warn('Riepilogo: elementi tabella non trovati');
        return;
    }
    const inputEl = dishId ? document.getElementById(String(dishId)) : null;
    const price = inputEl ? parseFloat(inputEl.dataset.price || inputEl.getAttribute('data-price') || 0) : 0;

    if (!dishName && inputEl) {
        // Try dataset/attributes first
        dishName = inputEl.dataset.name || inputEl.dataset.dishname || inputEl.getAttribute('data-name') || '';
        // If still empty, try to find a nearby element that holds the visible name (e.g. a <strong> in the same list item)
        if (!dishName) {
            const li = inputEl.closest('li') || inputEl.parentElement;
            try {
                const strong = li ? li.querySelector('strong') : null;
                if (strong && strong.textContent.trim()) dishName = strong.textContent.trim();
            } catch (e) {
                // ignore and keep dishName empty
            }
        }
    }
    const rowId = `riepilogo-item-${dishId}`;
    const existingRow = document.getElementById(rowId);

    if (Number(quantity) > 0) {
        // mostra la tabella e nascondi placeholder (non rimuoverlo dal DOM)
        itemsWrapper.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
        if (hr) hr.classList.remove('d-none');
        const qty = Number(quantity);
        const subtotal = qty * price;

        if (existingRow) {
            const qEl = existingRow.querySelector('.item-quantity');
            const sEl = existingRow.querySelector('.item-subtotal');
            if (qEl) qEl.textContent = qty;
            if (sEl) sEl.textContent = formatEuro(subtotal);
            existingRow.dataset.price = price;
            // ensure name cell updated if available
            const nameCell = existingRow.querySelector('td');
            if (nameCell && dishName) nameCell.innerHTML = escapeHtml(dishName);
        } else {
            const tr = document.createElement('tr');
            tr.id = rowId;
            tr.dataset.price = price;
            tr.innerHTML = `
                <td class="align-middle">${escapeHtml(dishName || '')}</td>
                <td class="text-end align-middle item-quantity">${qty}</td>
                <td class="text-end align-middle">${formatEuro(price)}</td>
                <td class="text-end align-middle item-subtotal">${formatEuro(subtotal)}</td>
            `;
            tbody.appendChild(tr);
        }
    } else {
        // rimuovi riga se quantity == 0
        if (existingRow && existingRow.parentNode) existingRow.parentNode.removeChild(existingRow);
    }

    // aggiorna totale e visibilità
    aggiornaTotale();
}

function aggiornaTotale() {
    const tbody = document.getElementById('riepilogo-table-body');
    const hr = document.getElementById('riepilogo-hr');
    const placeholder = document.getElementById('riepilogo-placeholder');
    const wrapper = document.getElementById('riepilogo-table-wrapper');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));
    if (!rows.length) {
        // mostra placeholder e nascondi tabella
        if (placeholder) placeholder.classList.remove('d-none');
        if (wrapper) wrapper.classList.add('d-none');
        if (hr) hr.classList.add('d-none');
        const totalValue = document.getElementById('riepilogo-total-value');
        if (totalValue) {
            totalValue.classList.add('d-none');
            const parts = totalValue.querySelectorAll('span');
            if (parts && parts.length >= 2) parts[1].textContent = '€0,00';
        }
        return;
    }

    let total = 0;
    rows.forEach(r => {
        const qtyEl = r.querySelector('.item-quantity');
        const qty = qtyEl ? parseInt(qtyEl.textContent, 10) : 0;
        const pr = parseFloat(r.dataset.price || 0);
        total += qty * pr;
    });

    const totalValue = document.getElementById('riepilogo-total-value');
    if (totalValue) {
        // mostra il blocco totale sotto la hr
        totalValue.classList.remove('d-none');
        const parts = totalValue.querySelectorAll('span');
        if (parts && parts.length >= 2) parts[1].textContent = formatEuro(total);
    }
    if (hr) hr.classList.remove('d-none');
    if (wrapper) wrapper.classList.remove('d-none');
}

function formatEuro(n) {
    return '€' + n.toFixed(2).replace('.', ',');
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}