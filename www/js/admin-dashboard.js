import { isToday, isTomorrow } from './common-functions.js';

document.getElementById("add-dish").addEventListener("click", () => {
    window.location.href = "admin-add-dish.php";
});
document.getElementById("manage-bookings").addEventListener("click", () => {
    window.location.href = "admin-bookings.php";
});
document.getElementById("manage-slots").addEventListener("click", () => {
    window.location.href = "admin-time-slots.php";
});

// Renderizza prenotazioni con piatti
async function renderBooking(bookings) {
    if (!Array.isArray(bookings)) return '';
    let html = '';

    for (const booking of bookings) {
        // fetch dei piatti della prenotazione
        let dishes = [];
        try {
            const res = await fetch(`api/reservation-details.php?reservation_id=${booking.reservation_id}`);
            if (res.ok) {
                const data = await res.json();
                // prendiamo solo i nomi dei piatti
                dishes = Array.isArray(data) ? data.map(d => d.name) : [];
            }
        } catch (e) {
            console.error("Errore fetch piatti per prenotazione", booking.reservation_id, e);
        }

        html += renderBookingItem(booking, dishes);
    }

    document.getElementById("booking-list").innerHTML = html;
}

// Render singola prenotazione con lista piatti
function renderBookingItem(booking, dishes) {
    const dt = new Date(booking.date_time);

    let when;
    if (isToday(booking.date_time)) {
        when = "Oggi";
    } else if (isTomorrow(booking.date_time)) {
        when = "Domani";
    } else {
        when =
            String(dt.getDate()).padStart(2, '0') + "/" +
            String(dt.getMonth() + 1).padStart(2, '0');
    }

    const time =
        String(dt.getHours()).padStart(2, '0') + ":" +
        String(dt.getMinutes()).padStart(2, '0');

    const dishesHtml = `<ul class="mb-2 list-unstyled">
                            ${dishes.map(d => `
                                <li>
                                    <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                                    ${d}
                                </li>`).join('')}
                        </ul>`;

    return `
        <div class="col-12 mb-3">
            <div class="p-3 border rounded-3 shadow-sm" id="reservation-${booking.reservation_id}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h3 class="h6 mb-1">
                            <i class="bi bi-calendar-event text-primary me-2"></i>
                            Prenotazione #${booking.reservation_id}
                        </h3>
                        <div class="small text-muted mb-1">
                            ${when} ${time} – ${booking.first_name} ${booking.last_name}
                        </div>
                        ${booking.badge || ''}
                    </div>
                    <strong mb-1>€ ${booking.total_amount}</strong>
                </div>
                ${dishesHtml}
            </div>
        </div>
    `;
}

// Render top dishes
function renderTopDishes(dishes) {
    if (!Array.isArray(dishes)) return '';
    return dishes.map(dish => `
        <div class="d-flex justify-content-between align-items-start">
            <span>${dish.name}<br><small class="text-muted">${dish.category_name}</small></span>
            <span class="badge bg-primary text-white">${dish.total_sold}</span>
        </div><hr/>
    `).join('');
}

// Prendi dati dashboard
async function getData() {
    const url = `api/admin-dashboard.php`;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        console.log("Fetched booking data:", data);

        await renderBooking(data.bookings); // qui mostriamo prenotazioni con piatti
        document.getElementById("bookings").textContent = data.bookings_count;
        document.getElementById("users").textContent = data.users_count;
        document.getElementById("earnings").textContent = "€"+Number(data.earnings_today).toFixed(2);
        document.getElementById("dishes").textContent = data.active_dishes;
        document.getElementById("top_dishes").innerHTML = renderTopDishes(data.top_dishes);
    } catch (error) {
        console.error("Error fetching booking data:", error);
    }
}

document.addEventListener('DOMContentLoaded', getData);
setInterval(getData, 30000);
