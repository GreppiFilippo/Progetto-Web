import { isToday, isTomorrow } from './common-functions.js';

let bookingsCache = [];
let currentPage = 1;
const resultsPerPage = 4;

document.getElementById('date').addEventListener('change', () => loadData(1));
document.getElementById('hour').addEventListener('change', () => loadData(1));  
document.getElementById('state').addEventListener('change', () => loadData(1));
document.getElementById('name').addEventListener('input', () => loadData(1));

async function loadData(page = 1) {
    const url = `utils/api-admin-bookings.php`;
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
        renderPagination(res.totalPages, currentPage);
    } catch (error) {
        console.error("Error fetching booking data:", error);
    }
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-outline-primary');
    if (!btn) return;

    const bookingId = btn.dataset.id;
    const data = bookingsCache.find(b => b.reservation_id == bookingId);
    if (!data) return;

    document.getElementById('modalContent').innerHTML = `
        <p><strong>ID:</strong> ${bookingId}</p>
        <p><strong>Nome:</strong> ${data.first_name}</p>
        <p><strong>Cognome:</strong> ${data.last_name}</p>
        <p><strong>Email:</strong> ${data.email}</p>
        <p><strong>Data:</strong> ${data.date_time}</p>
    `;

    const modal = new bootstrap.Modal(
        document.getElementById('bookingModal')
    );

    modal.show();
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
                    <span class="badge bg-secondary">${booking.num_dishes} piatti</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Totale</span>
                    <span>€ ${booking.total_amount}</span>
                </div>
                <hr class="my-2">
                <div class="btn-group g-1 d-flex">
                    <button type="button" class="btn btn-outline-primary" data-id="${booking.reservation_id}">
                        <i class="bi bi-eye text-primary"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger ms-1 " data-id="${booking.reservation_id}">
                        <i class="bi bi-trash text-danger"></i>
                    </button>
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

loadData(1);

