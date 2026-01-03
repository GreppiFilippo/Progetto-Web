<main class="container-fluid my-2">
    <div class="row dashboard m-0 mb-2">
        <div class="col-12">
            <!-- Titolo + icona -->
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-speedometer2 me-3" aria-hidden="true"></i>
                <h1 class="h6 mb-0">Dashboard Amministratore</h1>
            </div>
            <!-- Sottotitolo -->
            <p class="ts-4 fw-light mb-0">Panoramica generale del sistema</p>
        </div>
    </div>

    <!-- ICONS -->
    <section class="row mb-3 ">
        <div class="col mb-2 d-flex align-items-center col-md-3">
            <div class="bgicon bg-primary-subtle text-primary mx-3">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="d-flex flex-column align-items-start justify-content-center">
                <label for="bookings" class="small">Prenotazioni di oggi</label>
                <data id="bookings" value="47" class="h2 fw-bold mb-0"></data>
            </div>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="bgicon bg-warning-subtle mx-3">
                <i class="bi bi-people bg-warning-subtle text-warning mx-3"></i>
            </div>
            <div class="d-flex flex-column align-items-start justify-content-center">
                <label for="users" class="small">Utenti registrati</label>
                <data id="users" value="47" class="h2 fw-bold mb-0"></data>
            </div>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="bgicon bg-success-subtle mx-3">
                <i class="bi bi-currency-euro bg-success-subtle text-success mx-3"></i>
            </div>
            <div class="d-flex flex-column align-items-start justify-content-center">
                <label for="earnings" class="small">Incasso giornaliero</label>
                <data id="earnings" value="47" class="h2 fw-bold mb-0"></data>
            </div>
        </div>
        <div class="mb-2 d-flex align-items-center col-md-3">
            <div class="bgicon bg-info-subtle mx-3">
               <i class="bi bi-egg-fried bg-info-subtle text-info mx-3"></i>
            </div>
            <div class="d-flex flex-column align-items-start justify-content-center">
                <label for="dishes" class="small">Piatti attivi oggi</label>
                <data id="dishes" value="47" class="h2 fw-bold mb-0"></data>
            </div>
        </div>
    </section>

    <section class="mb-3">
        <header class="d-flex align-items-center">
            <h2 class="h5 m-0">Ultime prenotazioni</h2>
            <button class="btn admin-btn ms-auto" type="submit">Vedi tutte</button>
        </header>
    </section>

    <div class="row m-0">
        <!-- Contenuto principale -->
        <section class="col-12 col-md-8 mb-3">
            <div id="booking-list" class="row"></div>
        </section>

        <!-- Aside -->
        <aside class="col-12 col-md-4">
            <section class="row mx-0 mb-3">
                <h1 class="h5 p-0">Azioni rapide</h1>
                <button type="reset" class="btn admin-btn mb-1">
                    <i class="bi bi-plus-circle"></i>
                    Aggiungi piatto
                </button>
                <button type="submit" class="btn admin-btn">
                    <i class="bi bi-calendar-check"></i>
                    Gestisci prenotazioni
                </button>
            </section>

            <section class="row mx-0">
                <h1 class="h5 p-0">Piatti più ordinati</h1>
                <div id="top_dishes"></div>
            </section>
        </aside>
    </div>

    
    <?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</main>