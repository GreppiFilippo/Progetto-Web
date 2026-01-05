<main class="container-fluid my-3">

    <!-- Intestazione Dashboard -->
    <header class="dashboard border rounded-3 p-4 mb-4 shadow-sm">
        <h1 class="h3 mb-2 text-white">
            <i class="bi admin-icon bi-book text-white" aria-hidden="true"></i>
            Gestione menù
        </h1>
        <p class="mb-0 opacity-75 text-white">Gestisci i piatti disponibili nel menù</p>
    </header>

    <section class="row mb-3">
        <!-- FILTERS -->
        <div class="col-12 col-lg-10">
            <form class="row g-2">
                <div class="col-12 col-md-4 col-lg-3">
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
                <div class="col-12 col-md-4 col-lg-3">
                    <label for="state" class="form-label">Stato</label>
                    <select id="state" class="form-select">
                        <option value="all">Tutti</option>
                        <option value="available">Disponibile</option>
                        <option value="unavailable">Non disponibile</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-6">
                    <label for="name" class="form-label">Cerca</label>
                    <input type="text" class="form-control" placeholder="Cerca piatto per nome..." id="name">
                </div>
            </form>
        </div>
        <div class="col-12 col-lg-2 d-flex align-items-end mt-3 mt-lg-0">
            <button type="button" class="btn admin-btn btn-primary w-100" id="add-dish">
                <i class="bi admin-icon bi-plus-circle me-1"></i>
                Aggiungi piatto
            </button>
        </div>
    </section>


    <!-- DISH LIST -->
    <section id="dish_list" class="row m-0 g-2">
    </section>

    <div class="d-flex justify-content-center mt-3" id="pagination">
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