<?php
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}
?>
<main class="container-fluid py-4 px-3 px-md-4">
    <!-- Intestazione Dashboard -->
    <header class="dashboard border rounded-3 p-4 mb-4 shadow-sm">
        <h1 class="h3 mb-2 text-white">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard Amministratore
        </h1>
        <p class="mb-0 opacity-75 text-white">Panoramica generale del sistema</p>
    </header>

    <!-- ICONS -->
    <section class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-primary-subtle text-primary me-3">
                    <i class="bi bi-calendar-check fs-2"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="bookings" class="small text-muted mb-0">Prenotazioni Oggi</label>
                    <data id="bookings" value="47" class="h2 fw-bold mb-0"></data>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-warning-subtle text-warning me-3">
                    <i class="bi bi-people fs-2"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="users" class="small text-muted mb-0">Utenti Registrati</label>
                    <data id="users" value="342" class="h2 fw-bold mb-0"></data>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-success-subtle text-success me-3">
                    <i class="bi bi-currency-euro fs-2"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="earnings" class="small text-muted mb-0">Incasso Giornaliero</label>
                    <data id="earnings" value="487.50" class="h2 fw-bold mb-0"></data>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-white border rounded-3 p-3 shadow-sm d-flex align-items-center">
                <div class="bgicon bg-info-subtle text-info me-3">
                    <i class="bi bi-egg-fried fs-2"></i>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <label for="dishes" class="small text-muted mb-0">Piatti attivi oggi</label>
                    <data id="dishes" value="18" class="h2 fw-bold mb-0"></data>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-3">
        <!-- Aside -->
        <aside class="col-12 col-md-4 order-0 order-md-1">
            <section class="row mx-0 mb-3 shadow-sm border-3 rounded-3 p-3">
                <h1 class="h5 p-0">Azioni rapide</h1>
                <button type="button" class="btn admin-btn mb-1" id="add-dish">
                    <i class="bi bi-plus-circle"></i>
                    Aggiungi piatto
                </button>
                <button type="submit" class="btn admin-btn mb-1" id="manage-bookings">
                    <i class="bi bi-calendar-check"></i>
                    Gestisci prenotazioni
                </button>
                <button type="submit" class="btn admin-btn mb-1" id="manage-slots">
                    <i class="bi bi-clock"></i>
                    Gestisci slots
                </button>
            </section>

            <section class="row mx-0 shadow-sm border-3 rounded-3 p-3">
                <h1 class="h5 p-0">Piatti più ordinati</h1>
                <div id="top_dishes"></div>
            </section>
        </aside>

        <!-- Contenuto principale -->
        <section class="col-12 col-md-8 order-1 order-md-0">
            <div class="mb-3">
                <h2 class="h5 m-0">Prenotazioni di Oggi</h2>
            </div>
            <div id="booking-list" class="row g-3"></div>
            <div class="text-center mt-3">
                <a href="admin-bookings.php" class="text-decoration-none fw-bold">
                    Visualizza tutte le prenotazioni
                </a>
            </div>
        </section>
        <!-- TODO: metti le prenotazioni di oggi invece che le ultime -->
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