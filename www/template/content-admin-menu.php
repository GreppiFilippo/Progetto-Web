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
            <i class="bi admin-icon bi-book text-white me-2" aria-hidden="true"></i>
            Gestione menù
        </h1>
        <p class="mb-0 opacity-75 text-white">Gestisci i piatti disponibili nel menù</p>
    </header>

    <div class="row g-3 mb-4">
        <!-- FILTERS -->
        <section class="col-12 col-md-8">
            <form class="row g-3" autocomplete="off">
                <div class="col-12 col-sm-6">
                    <label for="category" class="form-label">Categoria</label>
                    <select id="category" class="form-select">
                        <option value="all">Tutte</option>
                        <?php foreach ($templateParams['categories'] as $categoria): ?>
                            <option value="<?php echo $categoria['category_id']; ?>">
                                <?php echo ucfirst($categoria['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <label for="state" class="form-label">Stato</label>
                    <select id="state" class="form-select">
                        <option value="all">Tutti</option>
                        <option value="available">Disponibile</option>
                        <option value="unavailable">Non disponibile</option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="name" class="form-label">Cerca</label>
                    <input type="text" class="form-control" placeholder="Cerca piatto per nome..." id="name"
                        autocomplete="off">
                </div>
            </form>
        </section>

        <!-- Aside -->
        <aside class="col-12 col-md-4 order-first order-md-last shadow-sm border-3 rounded-3 p-3">
            <section class="row mx-0 mb-3">
                <h2 class="h5 p-0 mb-2">Azioni rapide</h2>
                <button type="button" class="btn admin-btn mb-1" id="add-dish">
                    <i class="bi bi-plus-circle"></i>
                    Aggiungi piatto
                </button>
            </section>
        </aside>
    </div>

    <div class="row g-3">
        <!-- DISH LIST -->
        <section class="col-12 col-md-8">
            <div id="dish_list" class="row g-3"></div>
            <div class="d-flex justify-content-center mt-3" id="pagination"></div>
        </section>

        <!-- Aside spacer for alignment -->
        <div class="d-none d-md-block col-md-4"></div>
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