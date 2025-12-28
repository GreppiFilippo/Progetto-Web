<main class="container-fluid my-3">
    <header class="mb-3 text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-12 col-md mb-1">
                <h1 class="h3 mb-1">
                    <i class="bi admin-icon bi-calendar-check me-1" aria-hidden="true"></i>
                    Gestione Prenotazioni
                </h1>
                <p class="lead mb-0">Gestisci i piatti disponibili nel menù</p>
            </div>
        </div>
    </header>

    <!-- STATS --> 
    <!-- TODO integrare php -->
    <section class="row mb-3 m-0">
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="bookings" class="small">Prenotazioni di oggi</label>
                <data id="bookings" value="47" class="h2 fw-bold mb-0">47</data>
            </div>
            <i class="bi bi-calendar-check text-primary text-center col-6"></i>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="users" class="small">Utenti registrati</label>
                <data id="users" value="47" class="h2 fw-bold mb-0">342</data>
            </div>
            <i class="bi bi-people text-warning text-center col-6"></i>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">   
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="earnings" class="small">Incasso giornaliero</label>
                <data id="earnings" value="47" class="h2 fw-bold mb-0">1567.50</data>
            </div>
            <i class="bi bi-currency-euro text-success text-center col-6"></i>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">       
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="dishes" class="small">Piatti attivi oggi</label>
                <data id="dishes" value="47" class="h2 fw-bold mb-0">18</data>
            </div>
            <i class="bi bi-egg-fried text-info text-center col-6"></i>
        </div>
    </section>

    <form class="form-row g-2">
        <div class="col-12 col-md-3">
            <label for="date" class="form-label">Data</label>
            <input type="date" id="date" class="form-control"></select>
        </div>
        <div class="col-12 col-md-3">
            <label for="hour" class="form-label">Orario</label>
            <select id="hour" class="form-select"></select>
        </div>
        <div class="col-12 col-md-3">
            <label for="state" class="form-label">Stato</label>
            <select id="state" class="form-select"></select>
        </div>
        <div class="col-12 col-md-6">
            <label for="search" class="form-label">Cerca</label>
            <input type="text" class="form-control" placeholder="ID o nome utente..." id="search">
        </div>
    </form>

    <!-- DATA -->
    <section>
        
    </section>

</main>