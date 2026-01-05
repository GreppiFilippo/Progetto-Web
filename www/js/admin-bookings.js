import { isToday, isTomorrow, debounce, renderPagination } from './common-functions.js';

let bookingsCache = [];
let currentPage = 1;
const resultsPerPage = 4;

document.getElementById('date').addEventListener('change', () => loadData(1));
document.getElementById('hour').addEventListener('change', () => loadData(1));
document.getElementById('state').addEventListener('change', () => loadData(1));
document.getElementById('name').addEventListener('input', debounce(() => loadData(1), 150));

async function loadData(page = 1) {
    const url = `api/admin-bookings.php`;
    currentPage = page;
    try {
        const date = document.getElementById('date').value;
        const hour = document.getElementById('hour').value;
        const state = document.getElementById('state').value;
        const name = document.getElementById('name').value.trim();

        const params = new URLSearchParams({
            date,
            hour,
            state,
            name,
            page: currentPage,
            per_page: resultsPerPage
        });


        const response = await fetch(url + '?' + params);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const res = await response.json();
        const data = res.data;
        console.log("Fetched booking data:", bookingsCache);
        document.getElementById("bookings").innerHTML = data.today_bookings;
        document.getElementById("completed").innerHTML = data.completed;
        document.getElementById("preparing").innerHTML = data.preparing;
        document.getElementById("ready").innerHTML = data.ready;
        bookingsCache = data.bookings;
        renderBooking(bookingsCache);
        renderPagination(res.totalPages, currentPage, loadData);
    } catch (error) {
        console.error("Error fetching booking data:", error);
    }
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-outline-primary');
    if (!btn) return;

    const bookingId = btn.dataset.id;
    const status = bookingsCache.find(b => b.reservation_id == bookingId).status
    window.location.href = `admin-edit-reservation.php?reservation_id=${bookingId}&status=${status}`;
});


function renderBooking(bookings) {
    if (!Array.isArray(bookings)) return '';
    let html = '';
    bookings.forEach(booking => {
        html += renderBookingItem(booking);
    });
    document.getElementById("booking_list").innerHTML = html;
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
        <div class="col-12 col-md-6 g-md-2">
            <div class="card shadow-sm mb-2 h-100">
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
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Data</span>
                        <span>${displayDate}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span>Ora</span>
                        <span>${time}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        Numero piatti
                        <span class="badge bg-secondary">${booking.num_dishes + " "+ booking.num_dishes == 1 ? "Piatto" : "Piatti"}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Totale</span>
                        <span>€ ${booking.total_amount}</span>
                    </div>
                    <hr class="my-2">
                    <div class="btn-group g-1 d-flex">
                        <button type="button" class="admin-btn btn btn-outline-primary" data-id="${booking.reservation_id}">
                            <i class="bi bi-eye"></i>
                            Dettagli
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

loadData();
