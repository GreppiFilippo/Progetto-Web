<main class="container my-5">
    
    <!-- Intestazione Dashboard -->
    <header class="welcome border rounded-3 p-4 mb-3 shadow-sm">
    <h1 class="h3 mb-2 text-white">
        Benvenuto, <?php echo $templateParams["user"]["first_name"] . ' ' . $templateParams["user"]["last_name"]; ?>!
    </h1>
    <p class="mb-0 opacity-75 text-white">Gestisci le tue prenotazioni e consulta il menu del giorno</p>
    </header>

    <!-- Stato prenotazioni -->
    <section class="row g-3" aria-label="Stato prenotazioni">
    <div class="col-md-6">
        <section class="border rounded-3 p-4 shadow-sm" aria-label="Prenotazioni attive">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded-3 p-3" aria-hidden="true">
                <i class="bi bi-calendar-check text-primary fs-2" aria-hidden="true"></i>
            </div>
            <div class="ms-3">
            <h2 class="h6 mb-1">Prenotazioni attive</h2>
            <p class="h3 mb-0"><?php echo $templateParams["active_count"]; ?></p>
            </div>
        </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="border rounded-3 p-4 shadow-sm" aria-label="Prenotazioni completate">
        <div class="d-flex align-items-center">
            <div class="bg-success bg-opacity-10 rounded-3 p-3" aria-hidden="true">
                <i class="bi bi-check-circle text-success fs-2" aria-hidden="true"></i>
            </div>
            <div class="ms-3">
            <h2 class="h6 mb-1">Prenotazioni completate</h2>
            <p class="h3 mb-0"><?php echo $templateParams["completed_count"]; ?></p>
            </div>
        </div>
        </section>
    </div>
    </section>

    <div class="row g-3 mt-3">
    <div class="col-md-8">
        <section class="border rounded-3 p-4 shadow-sm" aria-labelledby="my-bookings-heading">
        <h2 id="my-bookings-heading" class="h5">Le Mie Prenotazioni</h2>

        <!--Inizio elenco prenotazioni-->
        <ul class="list-unstyled">
            <?php foreach($templateParams["reservations"] as $reservation): ?>
            <li class="mb-3 p-3 border rounded-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="h6 mb-1">
                        <i class="bi bi-calendar-event text-primary me-2" aria-hidden="true"></i>
                        <span>Pranzo - Oggi, 13:00</span>
                    </h3>
                    <span class="badge bg-success">Confermata</span>
                </div>
                <strong>€12.50</strong>
            </div>
            <ul class="list-unstyled small mb-2">
                <li>
                    <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                    <span>Pasta al Pomodoro</span>
                </li>
                <li>
                    <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                    <span>Pollo alla Griglia</span>
                </li>
                <li>
                    <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                    <span>Insalata Mista</span>
                </li>
            </ul>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#bookingDetailsModal">
                    <i class="bi bi-eye me-1" aria-hidden="true"></i>
                    <span>Dettagli</span>
                </button>
                <button class="btn btn-sm btn-outline-danger" type="button">
                    <i class="bi bi-trash me-1" aria-hidden="true"></i>
                    <span>Annulla</span>
                </button>
            </div>
            </li>
            <?php endforeach; ?>

            <li class="mb-3 p-3 border rounded-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h3 class="h6 mb-1">
                            <i class="bi bi-calendar-event text-primary me-2" aria-hidden="true"></i>
                            <span>Pranzo - Domani, 13:00</span>
                        </h3>
                        <span class="badge bg-warning text-dark">In Attesa</span>
                    </div>
                    <strong>€10.00</strong>
                </div>
                <ul class="list-unstyled small mb-2">
                    <li>
                        <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                        <span>Risotto ai funghi</span>
                    </li>
                    <li>
                        <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                        <span>Insalata Mista</span>
                    </li>
                    <li>
                        <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                        <span>Frutta di Stagione</span>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#bookingDetailsModal">
                        <i class="bi bi-eye me-1" aria-hidden="true"></i>
                        <span>Dettagli</span>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" type="button">
                        <i class="bi bi-trash me-1" aria-hidden="true"></i>
                        <span>Annulla</span>
                    </button>
                </div>
            </li>
        </ul>
        <div class="text-center mt-3">
            <a href="user-bookings.html" class="text-decoration-none fw-bold">Vedi tutte le prenotazioni</a>
        </div>
        </section>
    </div>

    <aside class="col-md-4" aria-label="Pannello azioni rapide">
        <section class="border rounded-3 p-4 shadow-sm">
        <div class="pb-3">
            <h2 class="h5 mb-0">Azioni Rapide</h2>
        </div>
        <a href="user-bookings.html" class="btn btn-lg text-white mb-1 w-100">
            <i class="bi bi-calendar-plus me-2" aria-hidden="true"></i>
            <span>Nuova Prenotazione</span>
        </a>
        <a href="menu.html" class="btn btn-lg mb-1 w-100">
            <i class="bi bi-book me-2" aria-hidden="true"></i>
            <span>Consulta Menu</span>
        </a>
        <a href="user-profile.html" class="btn btn-lg mb-1 w-100">
            <i class="bi bi-person me-2" aria-hidden="true"></i>
            <span>Modifica Profilo</span>
            </a>
        </section>
    </aside>
    </div>
</main>