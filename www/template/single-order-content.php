<?php
$r = $templateParams["reservation"];
$items = $templateParams["items"];
$tagsMap = $templateParams["tagsMap"];

$status = $r["status"];
?>

<main class="container my-5">
    <div class="row">
    <div class="col-md-8">
        <header class="border rounded-3 p-4 mb-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
            <h1 class="h4 mb-2">
                <i class="bi bi-receipt text-primary me-2" aria-hidden="true"></i>
                Ordine #<span><?php echo (int)$r["reservation_id"] ?></span>
            </h1>
            <p class="text-muted mb-0 pt-2">
                <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>
                <span><?php echo htmlspecialchars((string)formatWhen($r["date_time"]), ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
            </div>
            <div>
                <span class="badge <?php echo htmlspecialchars((string)reservationBadgeClass($status), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars((string)$status, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
        </div>
        </header>
        <section class="border rounded-3 p-4 mb-3 shadow-sm">
            <div class="bg-white border-0 pt-2 pb-3">
                <h2 class="h5 mb-0">
                    <i class="bi bi-bag-check me-2" aria-hidden="true"></i>
                    Piatti Ordinati
                </h2>
            </div>
            <ul class="list-unstyled mb-0">
                <?php if (count($items) === 0): ?>
                    <li class="py-3 text-muted">Nessun piatto associato a questo ordine.</li>
                <?php else: ?>
                    <?php foreach ($items as $item): 
                        $dishId = (int)$item["dish_id"];
                        $qty = (int)$item["quantity"];
                        $price = (float)$item["price"];
                        $lineTotal = $price * $qty;
                        $dishTags = $tagsMap[$dishId] ?? [];
                    ?>
                    <li class="d-flex justify-content-between align-items-start py-3 border-bottom">
                        <div class="flex-grow-1">
                            <h3 class="h6 mb-1">
                                <?php echo htmlspecialchars((string)$item["name"], ENT_QUOTES, 'UTF-8'); ?>
                                <span class="text-muted">x<?php echo $qty; ?></span>
                            </h3>
                            <p class="small text-muted mb-0"><?php echo htmlspecialchars((string)$item["description"], ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="mt-2">
                                <?php getTags($dishTags); ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <strong>
                                €<?php echo htmlspecialchars((string)formatEuro($lineTotal), ENT_QUOTES, 'UTF-8'); ?>
                            </strong>
                            <div class="small text-muted">
                                (€<?php echo htmlspecialchars((string)formatEuro($price), ENT_QUOTES, 'UTF-8'); ?> cad.)
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>
        <section class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <h2 class="h5 mb-0">
                <i class="bi bi-chat-left-text me-2" aria-hidden="true"></i>
                Note
            </h2>
            <div class="card-body">
                <p class="mb-0">
                    <?php if(!empty($r["notes"])) {
                        echo htmlspecialchars((string)$r["notes"], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo '<span class="text-muted">Nessuna nota aggiunta.</span>';
                    }?>
                </p>
            </div>
        </div>
        </section>
    </div>
    
    <div class="col-md-4">
        <aside aria-label="Pannello azioni rapide">
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-1">
                <h2 class="h4 mb-0">Informazioni Ordine</h2>
            </div>
            <div class="card-body">
            <div class="mb-3">
                <strong class="d-block text-muted small mb-1">Importo Totale</strong>
                <strong>€<?php echo htmlspecialchars((string)formatEuro($r["total_amount"]), ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
            <div class="mb-3">
                <strong class="d-block text-muted small mb-1">Stato</strong>
                <span class="badge <?php echo htmlspecialchars((string)reservationBadgeClass($status), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars((string)$status, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
            <div>
                <strong class="d-block text-muted small mb-1">Ritiro previsto</strong>
                <p class="mb-0">
                <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                Mensa Campus, Piazza Aldo Moro, 90, 47521 Cesena FC
                </p>
            </div>
            </div>
        </section>
        <section class="card border-0 shadow-sm">
            <div class="card-body">
            <div class="d-grid gap-2">
                <a href="user-dashboard.php" class="btn btn-outline-secondary text-white">
                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Torna alla Dashboard
                </a>
                <?php if (canCancelReservation($reservation['status'])) { ?>
                    <a class="btn btn-sm btn-outline-danger" type="button"
                    href="user-dashboard.php?cancel_id=<?php echo (int)$reservation['reservation_id']; ?>">
                        <i class="bi bi-trash me-1" aria-hidden="true"></i>
                        <span>Annulla ordine</span>
                    </a>
                <?php } ?>
            </div>
            </div>
        </section>
        </aside>
    </div>
    </div>
</main>
