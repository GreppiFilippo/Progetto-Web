<main class="container-fluid my-3">
    <!-- INTRO -->
    <section class="mb-3">
        <div class="row align-items-center">
            <div class="col-12 mb-2 mb-md-0">
                <h1 class="h3 mb-1">
                    <i class="bi admin-icon bi-receipt me-1" aria-hidden="true"></i>
                    Modifica prenotazione
                </h1>
                <p class="lead mb-0">
                    Gestisci lo stato e i dettagli della prenotazione
                </p>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="row g-3">

        <!-- DETTAGLI PRENOTAZIONE -->
        <div class="col-12 col-md-8">
            <div class="border rounded-3 p-3 p-md-4 shadow-sm h-100">

                <h2 class="h5 mb-3">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Dettagli prenotazione
                </h2>

                <!-- Meta info -->
                <div id="reservation-meta" class="mb-3 small">
                    <!-- popolato via JS -->
                </div>

                <h3 class="h6 mb-2">
                    <i class="bi bi-sticky me-2"></i>
                    Note aggiuntive
                </h3>
                <div id="reservation-notes" class="border rounded-2 p-2 text-muted mb-3">
                    <p>
                        <?php echo $reservation['notes']?>
                    </p>
                </div>

                <h3 class="h6 mb-2">
                    <i class="bi bi-card-list me-2"></i>
                    Piatti ordinati
                </h3>

                <ul id="reservation-dishes" class="list-group mb-3">
                    <!-- piatti -->
                </ul>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Totale</span>
                    <span id="reservation-total" class="fw-bold">
                        € 0.00
                    </span>
                </div>

            </div>
        </div>

        <!-- AZIONI -->
        <div class="col-12 col-md-4">
            <div class="border rounded-3 p-3 p-md-4 shadow-sm">

                <h2 class="h5 mb-3">
                    <i class="bi bi-gear me-2 text-primary"></i>
                    Stato prenotazione
                </h2>

                <label for="statusSelect" class="form-label">
                    Aggiorna stato
                </label>
                <select id="statusSelect" class="form-select mb-3">
                    <option value="Da Visualizzare">Da visualizzare</option>
                    <option value="In Preparazione">In preparazione</option>
                    <option value="Pronto al ritiro">Pronto al ritiro</option>
                    <option value="Completato">Completato</option>
                    <?php 
                        if ($status === "Da Visualizzare" || $status === "In Preparazione") {
                            echo '<option value="Annullato">Annullato</option>';
                        }
                    ?>
                </select>


                <div class="d-grid gap-2">
                    <button id="saveStatusBtn"
                            class="btn admin-btn" type="button">
                        <i class="bi bi-check-circle me-1"></i>
                        Salva modifiche
                    </button>
                </div>

            </div>
        </div>

    </section>

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
