import { isToday, isTomorrow } from './common-functions.js';

function renderBooking(bookings) {
    if (!Array.isArray(bookings)) return '';
    let html = '';
    bookings.forEach(booking => {
        html += renderBookingItem(booking);
    });
    return html;
}

function renderBookingItem(booking) {
    const dt = new Date(booking.date_time);
    const dayNum = dt.getDate();
    let displayDate;

    if (isToday(booking.date_time)) {
        displayDate = "Oggi";
    } else if (isTomorrow(booking.date_time)) {
        displayDate = "Domani";
    } else {
        displayDate = String(dayNum).padStart(2, '0') + "/" + String(dt.getMonth() + 1).padStart(2, '0');
    }

    const time = String(dt.getHours()).padStart(2, '0') + ":" + String(dt.getMinutes()).padStart(2, '0');

    return `
        <div class="card shadow-sm mb-2 col-md-4 g-md-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="h6 mb-0 text-truncate">
                        #${booking.reservation_id} ${booking.first_name} ${booking.last_name}
                    </h3>
                    ${booking.badge || ''}
                </div>

                <!-- Separatore -->
                <hr class="my-2">

                <!-- Data/Ora sotto -->
                <div class="small text-muted">
                    <span>Data</span>
                    <span>${displayDate} ${time}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>Numero piatti</span>
                    <span class="badge bg-secondary">${booking.num_dishes} piatti</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Totale</span>
                    <span>€ ${booking.total_amount}</span>
                </div>
                <hr class="my-2">
                <button class="btn admin-btn col-12" type="submit">Vedi dettagli</button>
            </div>
        </div>
    `;
}

function renderTopDishes(dishes) {
    if (!Array.isArray(dishes)) return '';
    let html = '';
    dishes.forEach(dish => {
        html += `
            <div class="d-flex justify-content-between align-items-start">
                <span>
                    ${dish.name}<br>
                    <small class="text-muted">${dish.category_name}</small>
                </span>
                <span class="badge bg-primary text-white">
                    ${dish.total_sold}
                </span>
            </div>
            <hr/>
        `;
    });
    return html;
}


async function getData() {
    const url = `utils/api-admin-dashboard.php`;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log("Fetched booking data:", data);

        const bookingsHtml = renderBooking(data.bookings);
        document.getElementById("booking-list").innerHTML = bookingsHtml;
        document.getElementById("bookings").innerHTML = data.bookings_count;
        document.getElementById("users").innerHTML = data.users_count;
        document.getElementById("earnings").innerHTML = data.earnings_today;
        //document.getElementById("active-dishes").textContent = data.active_dishes;
        document.getElementById("top_dishes").innerHTML = renderTopDishes(data.top_dishes);
    } catch (error) {
        console.error("Error fetching booking data:", error);
    }
}

document.addEventListener('DOMContentLoaded', getData);


setInterval(() => {
    getData();
}, 30000);