document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const reservationId = params.get("reservation_id");

    if (!reservationId) return console.error("reservation_id mancante");

    // Fetch dei piatti
    fetch(`api/reservation-details.php?reservation_id=${reservationId}`)
        .then(res => res.json())
        .then(dishes => {
            if (!Array.isArray(dishes) || dishes.length === 0) {
                document.getElementById("reservation-dishes").innerHTML =
                    '<li class="py-3 text-muted">Nessun piatto associato a questa prenotazione.</li>';
                return;
            }
            renderDishes(dishes);
        })
        .catch(err => console.error("Errore fetch piatti:", err));

    const saveStatusBtn = document.getElementById('saveStatusBtn');

    if (saveStatusBtn) {
        saveStatusBtn.addEventListener("click", () => {
            const status = document.getElementById('statusSelect').value;
            if (!status) {
                alert("Seleziona un nuovo stato prima di salvare");
                return;
            }
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
    }
});

function renderDishes(dishes) {
    const list = document.getElementById("reservation-dishes");

    list.innerHTML = dishes.map(dish => {
        const price = parseFloat(dish.price);
        const quantity = dish.quantity || 1;
        const subtotal = price * quantity;

        return `
        <li class="d-flex justify-content-between align-items-start py-3 border-bottom">
            <div class="flex-grow-1">
                <h3 class="h6 mb-1">
                    ${dish.name}
                    <span class="text-muted">x${quantity}</span>
                </h3>
                <p class="small text-muted mb-0">${dish.description || ''}</p>
            </div>
            <div class="text-end">
                <strong>€${subtotal.toFixed(2)}</strong>
                <div class="small text-muted">(€${price.toFixed(2)} cad.)</div>
            </div>
        </li>`;
    }).join('');
}