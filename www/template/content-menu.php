<?php
if (!defined('IN_APP')) {
  http_response_code(404);
  exit;
}
?>
<main class="container my-5">

    <!-- Page Header -->
    <header class="text-center">
        <h1 class="fw-bold">Menu</h1>
        <p class="lead text-muted">
            <i class="bi bi-calendar-event me-2" aria-hidden="true"></i>
            <?php
                $giorni = ['domenica','lunedì','martedì','mercoledì','giovedì','venerdì','sabato'];
                $mesi   = ['','gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'];

                $oggi = new DateTime('now', new DateTimeZone('Europe/Rome'));
                $giornoSettimana = $giorni[(int)$oggi->format('w')];
                $giorno = (int)$oggi->format('j');
                $mese = $mesi[(int)$oggi->format('n')];
                $anno = $oggi->format('Y');

                echo htmlspecialchars("{$giornoSettimana} {$giorno} {$mese} {$anno}", ENT_QUOTES, 'UTF-8');
            ?>
        </p>
    </header>

    <!-- Filter -->
    <form action="menu.php" method="GET" id="filterForm" class="card my-4 shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md">
                    <label for="categoria" class="form-label">Categoria</label>
                    <?php
                        $categoriaSelezionata = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
                        $ricerca = $_GET['cerca'] ?? "";
                    ?>
                    <select name="categoria" id="categoria" class="form-select" aria-describedby="resultsAnnounce">
                        
                        <!-- Opzione "Tutte le categorie" -->
                        <option value="0" <?php if ($categoriaSelezionata == 0) echo 'selected'; ?>>
                            Tutte le categorie
                        </option>

                        <!-- Opzioni categorie dal database -->
                        <?php foreach($templateParams['categorie'] as $categoria): ?>
                            <option 
                                value="<?php echo $categoria['category_id']; ?>" 
                                <?php if ($categoriaSelezionata == $categoria['category_id']) echo 'selected'; ?>
                            >
                                <?php echo ucfirst(htmlspecialchars((string) $categoria['category_name'], ENT_QUOTES, 'UTF-8')); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md">
                    <label for="cerca" class="form-label">Cerca piatto</label>
                    <input 
                        type="search" 
                        name="cerca" 
                        id="cerca" 
                        class="form-control" 
                        placeholder="Cerca per nome..." 
                        value="<?php echo htmlspecialchars($ricerca); ?>"
                        aria-describedby="resultsAnnounce"
                    />                
                </div>
            </div>
        </div>
    </form>

    <!-- Live region for screen readers -->
    <div id="resultsAnnounce" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>

    <?php 
    $totalePiatti = 0;
    foreach($templateParams["categorie"] as $categoria):
        if ($categoriaSelezionata != 0 && $categoriaSelezionata != $categoria['category_id']) {
            continue;
        }

        // Recupera e filtra i piatti per la categoria corrente
        $piatti = $dbh->getAllDishes($categoria['category_id']);
        $piattiFiltrati = array_filter($piatti, function($piatto) use ($ricerca) {
            return empty($ricerca) || mb_stripos($piatto['name'], $ricerca) !== false;
        });

        // Se la ricerca è attiva e non ci sono piatti filtrati, salta la categoria
        if (empty($piattiFiltrati)) {
            continue;
        }
        
        $totalePiatti += count($piattiFiltrati);
    ?>

        <!-- Menu Items -->
        <section class="my-5" data-category-id="<?php echo $categoria['category_id']; ?>">
            <h2 class="h4 mb-3"><?php echo ucfirst(htmlspecialchars((string) $categoria['category_name'], ENT_QUOTES, 'UTF-8')); ?></h2>
            <hr>

            <ul class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 list-unstyled" id="menuList-<?php echo $categoria['category_id']; ?>">

            <?php
                foreach($piattiFiltrati as $piatto):
            ?>
                <li class="col">
                <div class="card h-100 shadow-sm">

                    <div class="ratio ratio-16x9">
                        <img
                            src="<?php echo UPLOAD_DIR . $piatto['image']; ?>"
                            class="img-fluid rounded-top"
                            alt="<?php echo htmlspecialchars($piatto['description']); ?>"
                        />
                    </div>

                    <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <!-- Titolo -->
                        <h3 class="h5 mb-0 card-title"><?php echo htmlspecialchars($piatto['name']); ?></h3>
                        <!-- Stato disponibilità -->
                        <?php availableBadge($piatto['stock']); ?>
                    </div>

                    <!-- Descrizione -->
                    <p class="card-text text-muted small mb-2">
                        <?php echo htmlspecialchars($piatto['description']); ?>
                    </p>

                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <!-- Tipologia -->
                        <?php getTags($dbh->getDietaryTagsForDish($piatto["dish_id"])); ?>
                        <!-- Calorie -->
                        <span class="text-muted"><?php echo htmlspecialchars($piatto['calories']); ?> kcal</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Disponibilità -->
                        <small class="text-muted">
                            <i class="bi bi-basket me-1" aria-hidden="true"></i>
                            Disponibili: <strong><?php echo htmlspecialchars($piatto['stock']); ?></strong>
                        </small>
                        <strong>€<?php echo htmlspecialchars($piatto['price']); ?></strong>
                    </div>
                    </div>

                </div>
                </li>
            <?php endforeach; ?>
            </ul>
        </section>
    <?php endforeach; ?>

    <div id="menuContainer" data-total-results="<?php echo $totalePiatti; ?>"></div>

    <!-- Login Alert -->
    <?php if (!isUserLoggedIn()): ?>
        <div class="alert d-flex align-items-center p-3 rounded-3">
        <i class="bi bi-info-circle-fill fs-4 me-3" aria-hidden="true"></i>
        <span>
            <strong>Accedi per prenotare</strong>
            - Per prenotare i tuoi piatti preferiti devi prima
            <a href="login.php" class="alert-link text-decoration-none">accedere</a> o 
            <a href="register.php" class="alert-link text-decoration-none">registrarti</a>.
        </span>
        </div>
    <?php endif; ?>

</main>