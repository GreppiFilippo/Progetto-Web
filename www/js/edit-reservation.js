document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const reservationId = params.get("reservation_id");

    if (!reservationId) return console.error("reservation_id mancante");
    console.log("si")
    // Fetch dei piatti
    fetch(`api/reservation-details.php?reservation_id=${reservationId}`)
        .then(res => res.json())
        .then(dishes => {
            if (!Array.isArray(dishes) || dishes.length === 0) {
                document.getElementById("reservation-dishes").innerHTML =
                    '<li class="list-group-item text-muted">Nessun piatto</li>';
                document.getElementById("reservation-total").textContent = '€ 0.00';
                return;
            }
            renderDishes(dishes);
        })
        .catch(err => console.error("Errore fetch piatti:", err));

    const btn = document.getElementById('saveStatusBtn');

    btn.addEventListener("click", () => {
        const status = document.getElementById('statusSelect').value;
        fetch('api/admin-update-reservation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `reservation_id=${reservationId}&status=${encodeURIComponent(status)}`
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = "admin-bookings.php";
            } else {
                alert("Errore nell'aggiornamento: " + (result.message || ""));
            }
        })
        .catch(err => {
            console.error("Errore fetch:", err);
            alert("Errore durante la richiesta");
        });
    });

    
});

// Funzione per renderizzare i piatti
function renderDishes(dishes) {
    const list = document.getElementById("reservation-dishes");
    const totalSpan = document.getElementById("reservation-total");

    let total = 0;
    console.log("ciao");
    list.innerHTML = dishes.map(dish => {
        const price = parseFloat(dish.price);
        const quantity = dish.quantity || 1;
        const subtotal = price * quantity;
        total += subtotal;

        return `
        <li class="list-group-item d-flex justify-content-between">
            <div>
                <strong>${dish.name}</strong>
                <div class="small text-muted">${dish.description || ''}</div>
                <div class="small text-muted">Quantità: ${quantity} x €${price}</div>
            </div>
            <span>€ ${subtotal.toFixed(2)}</span>
        </li>`;
    }).join('');

    totalSpan.textContent = `€ ${total.toFixed(2)}`;
}


