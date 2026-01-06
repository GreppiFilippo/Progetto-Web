<?php
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}
?>
<main class="container-fluid my-3">
    <!-- Intestazione Dashboard -->
    <header class="dashboard border rounded-3 p-4 mb-4 shadow-sm">
        <h1 class="h3 mb-2 text-white">
            <i class="bi admin-icon bi-clock text-white" aria-hidden="true"></i>
            Gestione Slot Orari
        </h1>
        <p class="mb-0 opacity-75 text-white">Visualizza, modifica o elimina gli slot disponibili</p>
    </header>

    <!-- FILTERS -->
    <form class="row g-2 mb-3">
        <h2 class="visually-hidden">Filtra slots</h2>
        <div class="col-12 col-md-4">
            <label for="slot_date" class="form-label">Data</label>
            <input type="date" class="form-control" id="slot_date" required>
        </div>
        <div class="col-12 col-md-4">
            <label for="slot_time" class="form-label">Orario</label>
            <input type="time" class="form-control" id="slot_time" step="900" id="slot_time" required>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-end">
            <button type="button" class="btn admin-btn w-100" id="add_slot">
                <i class="bi bi-bookmark-plus me-1" aria-hidden="true"></i> Aggiungi
            </button>
        </div>
        <div class="col-12">
            <div id="slot-error-message" class="alert alert-danger d-none" role="alert" aria-live="assertive"></div>
            <div id="slot-success-message" class="alert alert-success d-none" role="alert" aria-live="polite"></div>
        </div>
    </form>

    <!-- SLOT LIST -->
    <div id="slot_list" class="row m-0 g-2" aria-live="polite" aria-atomic="false">
    </div>

    <!-- SCRIPTS -->
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