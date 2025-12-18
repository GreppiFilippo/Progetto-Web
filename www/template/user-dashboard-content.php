<?php
if (!defined('IN_APP')) {
    http_response_code(403);
    exit('Access denied');
}
?>
<main class="container my-5">
    
    <!-- Intestazione Dashboard -->
    <header class="welcome border rounded-3 p-4 mb-3 shadow-sm">
    <h1 class="h3 mb-2 text-white">
        Benvenuto, <?php echo htmlspecialchars($templateParams["user"]["first_name"] . ' ' . $templateParams["user"]["last_name"], ENT_QUOTES, 'UTF-8'); ?>!
    </h1>
    <p class="mb-0 opacity-75 text-white">Gestisci le tue prenotazioni e consulta il menu del giorno</p>
    </header>

    <!-- Stato prenotazioni -->
    <section class="row g-3" aria-label="Stato prenotazioni">
    <div class="col-md-6">
        <section class="border rounded-3 p-4 shadow-sm" aria-label="Prenotazioni attive">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded-3 p-3" aria-hidden="true">
                <i class="bi bi-calendar-check text-primary fs-2" aria-hidden="true"></i>
            </div>
            <div class="ms-3">
            <h2 class="h6 mb-1">Prenotazioni attive</h2>
            <p class="h3 mb-0"><?php echo htmlspecialchars((string)$templateParams["active_count"], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="border rounded-3 p-4 shadow-sm" aria-label="Prenotazioni completate">
        <div class="d-flex align-items-center">
            <div class="bg-success bg-opacity-10 rounded-3 p-3" aria-hidden="true">
                <i class="bi bi-check-circle text-success fs-2" aria-hidden="true"></i>
            </div>
            <div class="ms-3">
            <h2 class="h6 mb-1">Prenotazioni completate</h2>
            <p class="h3 mb-0"><?php echo htmlspecialchars((string)$templateParams["completed_count"], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
        </section>
    </div>
    </section>

    <div class="row g-3 mt-3">
    <div class="col-md-8">
        <section class="border rounded-3 p-4 shadow-sm" aria-labelledby="my-bookings-heading">
        <h2 id="my-bookings-heading" class="h5">Le Mie Prenotazioni</h2>

        <?php if (empty($templateParams["reservations"])): ?>
            <div class="alert alert-info mb-0" role="alert">
                Nessuna prenotazione trovata.
            </div>
        <?php else: ?>

            <!--Inizio elenco prenotazioni-->
            <ul class="list-unstyled">
                <?php foreach($templateParams["reservations"] as $reservation): ?>
                    <?php
                        $status = (string)$reservation["status"];
                        $when   = formatWhen($reservation["date_time"]);
                        $total  = formatEuro($reservation["total_amount"]);
                        $items  = $reservation["items"] ?? [];

                        $badgeClass = reservationBadgeClass($status);
                        $canCancel = canCancelReservation($status);
                    ?>
                <li class="mb-3 p-3 border rounded-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h3 class="h6 mb-1">
                            <i class="bi bi-calendar-event text-primary me-2" aria-hidden="true"></i>
                            <span>
                                Pranzo - <?php echo htmlspecialchars((string)$when, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </h3>
                        <span class="badge <?php echo htmlspecialchars((string)$badgeClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$status, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <strong>€<?php echo htmlspecialchars((string)$total, ENT_QUOTES, 'UTF-8'); ?></strong>
                </div>
                <?php if (!empty($items)): ?>
                    <ul class="list-unstyled small mb-2">
                        <?php foreach($items as $item): ?>
                            <li>
                                <i class="bi bi-check2 text-success me-2" aria-hidden="true"></i>
                                <span><?php echo htmlspecialchars((string)$item["name"], ENT_QUOTES, 'UTF-8'); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="small text-body-secondary mb-2">Nessun piatto associato.</p>
                <?php endif; ?>
                <div class="d-flex gap-2">

                    <!-- DETTAGLI: vai a single-order.php -->
                    <a class="btn btn-sm btn-outline-primary" type="button"
                    href="single-order.php?reservation_id=<?php echo (int)$reservation['reservation_id']; ?>">
                        <i class="bi bi-eye me-1" aria-hidden="true"></i>
                        <span class="text-white">Dettagli</span>
                    </a>

                    <!-- ANNULLA: appare solo se canCancel è true -->
                    <?php if (canCancelReservation($reservation['status'])) { ?>
                        <a class="btn btn-sm" type="button"
                        href="user-dashboard.php?cancel_id=<?php echo (int)$reservation['reservation_id']; ?>">
                            <i class="bi bi-trash me-1" aria-hidden="true"></i>
                            <span>Annulla ordine</span>
                        </a>
                    <?php } ?>

                </div>
                <?php endforeach; ?> 
            </ul>
        <?php endif; ?>
       <div class="text-center mt-3">
            <?php if ($showAll): ?>
                <a href="user-dashboard.php" class="text-decoration-none fw-bold">
                    Mostra meno
                </a>
            <?php else: ?>
                <a href="user-dashboard.php?show_all=1" class="text-decoration-none fw-bold">
                    Visualizza tutte le prenotazioni
                </a>
            <?php endif; ?>
        </div>
        </section>
    </div>

    <aside class="col-md-4" aria-label="Pannello azioni rapide">
        <section class="border rounded-3 p-4 shadow-sm">
        <div class="pb-3">
            <h2 class="h5 mb-0">Azioni Rapide</h2>
        </div>
        <a href="user-bookings.php" class="btn btn-lg text-white mb-1 w-100">
            <i class="bi bi-calendar-plus me-2" aria-hidden="true"></i>
            <span>Nuova Prenotazione</span>
        </a>
        <a href="menu.php" class="btn btn-lg mb-1 w-100">
            <i class="bi bi-book me-2" aria-hidden="true"></i>
            <span>Consulta Menu</span>
        </a>
        <a href="user-profile.php" class="btn btn-lg mb-1 w-100">
            <i class="bi bi-person me-2" aria-hidden="true"></i>
            <span>Modifica Profilo</span>
            </a>
        </section>
    </aside>
    </div>
</main>
