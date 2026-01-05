<main class="container-fluid p-4">
    <!-- Intestazione Dashboard -->
    <header class="dashboard border rounded-3 p-4 mb-4 shadow-sm">
        <h1 class="h3 mb-2 text-white">
            <i class="bi admin-icon bi-calendar-check text-white" aria-hidden="true"></i>
            Gestione Prenotazioni
        </h1>
        <p class="mb-0 opacity-75 text-white">Gestisci i piatti disponibili nel menù</p>
    </header>

    <!-- ICONS -->
    <section class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-primary-subtle text-primary me-3">
                    <i class="bi bi-calendar-check fs-2" aria-hidden="true"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="bookings" class="small">Prenotazioni oggi</label>
                    <data id="bookings" value="47" class="h2 fw-bold mb-0">47</data>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-warning-subtle text-warning me-3">
                    <i class="bi bi-check-square-fill fs-2" aria-hidden="true"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="completed" class="small">Completati oggi</label>
                    <data id="completed" value="47" class="h2 fw-bold mb-0">342</data>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-success-subtle text-success me-3">
                    <i class="bi bi-arrow-clockwise fs-2" aria-hidden="true"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="preparing" class="small">In preparazione oggi</label>
                    <data id="preparing" value="47" class="h2 fw-bold mb-0"></data>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-info-subtle text-info me-3">
                    <i class="bi bi-box-seam fs-2" aria-hidden="true"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="ready" class="small">Pronti al ritiro oggi</label>
                    <data id="ready" value="47" class="h2 fw-bold mb-0">18</data>
                </div>
            </div>
        </div>
    </section>

    <form class="row g-3 mb-4">
        <div class="col-12 col-md-2">
            <label for="date" class="form-label">Data</label>
            <input type="date" id="date" class="form-control"></select>
        </div>
        <div class="col-12 col-md-2">
            <label for="hour" class="form-label">Orario</label>
            <select id="hour" class="form-select">
                <option value="" selected>--</option>
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
    <section id="booking_list" class="row g-3">
    </section>

    <div class="d-flex justify-content-center mt-4" id="pagination">
        <!-- bottoni paginazione generati da JS -->
    </div>

    <?php
    if (isset($templateParams["js"])):
        foreach ($templateParams["js"] as $script):
            ?>
            <script src="<?php echo $script; ?>" type="module"></script>
            <?php
        endforeach;
    endif;
    ?>

</main>