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
    <section class="row mb-2 m-0">
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="bookings" class="small">Prenotazioni di oggi</label>
                <data id="bookings" value="47" class="h2 fw-bold mb-0">47</data>
            </div>
            <i class="bi bi-calendar-check text-primary text-center col-6"></i>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="completed" class="small">Completati</label>
                <data id="completed" value="47" class="h2 fw-bold mb-0">342</data>
            </div>
            <i class="bi bi-people text-warning text-center col-6"></i>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">   
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="preparing" class="small">In preparazione</label>
                <data id="preparing" value="47" class="h2 fw-bold mb-0">1567.50</data>
            </div>
            <i class="bi bi-currency-euro text-success text-center col-6"></i>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">       
            <div class="d-flex flex-column align-items-start justify-content-center col-6">
                <label for="ready" class="small">Pronti al ritiro</label>
                <data id="ready" value="47" class="h2 fw-bold mb-0">18</data>
            </div>
            <i class="bi bi-egg-fried text-info text-center col-6"></i>
        </div>
    </section>

    <form class="row g-2 mb-2">
        <div class="col-12 col-md-2">
            <label for="date" class="form-label">Data</label>
            <input type="date" id="date" class="form-control"></select>
        </div>
        <div class="col-12 col-md-2">
            <label for="hour" class="form-label">Orario</label>
            <select id="hour" class="form-select">
                <option value="" selected>--</option>
                <?php
                    $start = new DateTime("11:45");
                    $end = new DateTime("14:15");

                    // intervallo di 15 minuti
                    $interval = new DateInterval("PT5M");

                    for ($time = $start; $time <= $end; $time->add($interval)) {
                        echo '<option value="' . $time->format("H:i") . '">' . $time->format("H:i") . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <label for="state" class="form-label">Stato</label>
            <select id="state" class="form-select">
                <option value="all" selected>Tutti</option>
                <option value="Completato">Completate</option>
                <option value="Pronto al Ritiro">Da ritirare</option>
                <option value="In Preparazione">In preparazione</option>
                <option value="Da Visualizzare">Da visualizzare</option>
                <option value="Annullato">Annullate</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="name" class="form-label">Cerca</label>
            <input type="text" class="form-control" placeholder="Nome utente..." id="name">
        </div>
    </form>

    <!-- DATA -->
    <section id="booking_list" class="row m-0"> 
    </section>

    <div class="d-flex justify-content-center mt-3" id="pagination">
        <!-- bottoni paginazione generati da JS -->
    </div>

    <!-- POPUP -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" id="modalContent">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Chiudi
                    </button>
                </div>
            </div>
        </div>
    </div>



    <?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>" type="module"></script>
    <?php
        endforeach;
    endif;
    ?>

</main>