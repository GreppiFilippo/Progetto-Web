<main class="container-fluid my-3">
    <!-- INTRO -->
    <section class="mb-2">
        <div class="row align-items-center">
            <div class="col-12 col-md mb-1">
                <h1 class="h3 mb-1">
                    <i class="bi admin-icon bi-book me-1" aria-hidden="true"></i>
                    Gestione menù
                </h1>
                <p class="lead mb-0">Gestisci i piatti disponibili nel menù</p>
            </div>
            
            <div class="col-12 col-md-auto text-md-end">
                <button type="reset" class="btn admin-btn btn-primary w-100 w-md-auto">
                    <i class="bi admin-icon bi-plus-circle me-1"></i>
                    Aggiungi piatto
                </button>
            </div>
        </div>
    </section>

    <!-- FILTERS -->
    <form class="row g-2 mb-3">
        <div class="col-12 col-md-3">
            <label for="category" class="form-label">Categoria</label>
            <select id="category" class="form-select">
                <option value="all" <?php if ($categoriaSelezionata == 'all') echo 'selected'; ?>>Tutte</option>
                <?php foreach($templateParams['categories'] as $categoria): ?>
                    <option 
                        value="<?php echo $categoria['category_id']; ?>" 
                        <?php if ($categoriaSelezionata == $categoria['category_id']) echo 'selected'; ?>
                    >
                        <?php echo ucfirst($categoria['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <label for="state" class="form-label">Stato</label>
            <select id="state" class="form-select">
                <option value="all" <?php if ($statoSelezionato == 'all') echo 'selected'; ?>>Tutti</option>
                <option value="available" <?php if ($statoSelezionato == 'available') echo 'selected'; ?>>Disponibile</option>
                <option value="unavailable" <?php if ($statoSelezionato == 'unavailable') echo 'selected'; ?>>Non disponibile</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="name" class="form-label">Cerca</label>
            <input type="text" class="form-control" placeholder="Cerca piatto per nome..." id="name">
        </div>
    </form>

    <!-- DISH LIST -->
    <section id="dish_list">
    </section>

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