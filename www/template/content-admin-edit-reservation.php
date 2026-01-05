<main class="container my-5">
    <div class="row">
        <!-- DETTAGLI PRENOTAZIONE -->
        <div class="col-md-8">
            <header class="border rounded-3 p-4 mb-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h1 class="h4 mb-2">
                            <i class="bi bi-receipt text-primary me-2" aria-hidden="true"></i>
                            Prenotazione #<?php echo (int) $reservation['reservation_id']; ?>
                        </h1>
                        <p class="text-muted mb-0 pt-2">
                            <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>
                            <?php echo htmlspecialchars((string) formatWhen($reservation["date_time"]), ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                    <div>
                        <span
                            class="badge <?php echo htmlspecialchars((string) reservationBadgeClass($reservation["status"]), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars((string) $reservation["status"], ENT_QUOTES, 'UTF-8'); ?>
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
                <ul id="reservation-dishes" class="list-unstyled mb-0">
                    <!-- piatti popolati via JS -->
                </ul>
            </section>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h2 class="h5 mb-0">
                        <i class="bi bi-chat-left-text me-2" aria-hidden="true"></i>
                        Note
                    </h2>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <?php if (!empty($reservation['notes'])) {
                            echo htmlspecialchars((string) $reservation['notes'], ENT_QUOTES, 'UTF-8');
                        } else {
                            echo '<span class="text-muted">Nessuna nota aggiunta.</span>';
                        } ?>
                    </p>
                </div>
            </section>
        </div>

        <!-- AZIONI -->
        <div class="col-md-4">
            <aside aria-label="Pannello azioni amministratore">
                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-1">
                        <h2 class="h4 mb-0">Informazioni Prenotazione</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">Importo Totale</strong>
                            <strong>€
                                <?php echo htmlspecialchars((string) formatEuro($reservation["total_amount"]), ENT_QUOTES, 'UTF-8'); ?></strong>
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

                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-1">
                        <h2 class="h4 mb-0">Aggiorna Stato</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">Stato Corrente</strong>
                            <span
                                class="badge <?php echo htmlspecialchars((string) reservationBadgeClass($reservation["status"]), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars((string) $reservation["status"], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <?php if ($reservation["status"] !== "Completato" && $reservation["status"] !== "Annullato"): ?>
                            <label for="statusSelect" class="form-label">Cambia stato</label>
                            <select id="statusSelect" class="form-select mb-3">
                                <?php
                                $currentStatus = $reservation["status"];

                                // Placeholder iniziale
                                echo '<option value="" selected disabled>Seleziona nuovo stato</option>';

                                // Transizioni valide
                                if ($currentStatus === "Da Visualizzare") {
                                    echo '<option value="In Preparazione">In preparazione</option>';
                                    echo '<option value="Annullato">Annullato</option>';
                                } elseif ($currentStatus === "In Preparazione") {
                                    echo '<option value="Pronto al ritiro">Pronto al ritiro</option>';
                                    echo '<option value="Annullato">Annullato</option>';
                                } elseif ($currentStatus === "Pronto al ritiro") {
                                    echo '<option value="Completato">Completato</option>';
                                    echo '<option value="Annullato">Annullato</option>';
                                }
                                ?>

                            </select>

                            <div class="d-grid">
                                <button id="saveStatusBtn" class="btn admin-btn" type="button">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Salva modifiche
                                </button>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Questo ordine non può più essere modificato.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-grid">
                            <a href="admin-bookings.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Torna alle Prenotazioni
                            </a>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
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