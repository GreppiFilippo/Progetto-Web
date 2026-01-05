<main class="container-fluid my-3">
    <!-- INTRO -->
    <section class="mb-2">
        <div class="row align-items-center">
            <div class="col-12 col-md mb-1">
                <h1 class="h3 mb-1">
                    <i class="bi admin-icon bi-clock me-1" aria-hidden="true"></i>
                    Gestione Slot Orari
                </h1>
                <p class="lead mb-0">Visualizza, modifica o elimina gli slot disponibili</p>
            </div>
        </div>
    </section>

    <!-- FILTERS -->
    <form class="row g-2 mb-3">
        <div class="col-12 col-md-4">
            <label for="slot_date" class="form-label">Data</label>
            <input type="date" class="form-control" id="slot_date" required>
        </div>
        <div class="col-12 col-md-4">
            <label for="slot_time" class="form-label">Orario</label>
            <input type="time" class="form-control" id="slot_time" step="900" id="slot_time" required>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-end">
            <button type="button" class="btn btn-secondary w-100" id="add_slot">
                <i class="bi bi-bookmark-plus me-1"></i> Aggiungi
            </button>
        </div>
    </form>

    <!-- SLOT LIST -->
    <section id="slot_list" class="row m-0 g-2">
    </section>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center mt-3" id="pagination"></div>

    <!-- SCRIPTS -->
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
