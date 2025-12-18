<?php
if (!defined('IN_APP')) {
    http_response_code(403);
    exit('Access denied');
}
?>
<main class="container">
    <form action="#" method="post" class="row g-4 my-5">
        <section class="col-xl-7">
            <h1 class="h3"><i class="bi bi-calendar-plus me-2" aria-hidden="true"></i>Nuova Prenotazione</h1>

            <!-- Step 1: Seleziona Data e Orario -->
            <fieldset class="card my-4 rounded-4 border-0 shadow-sm">
                <legend class="card-header h5 mb-0 rounded-top">
                    <span class="badge bg-white me-2">1</span>
                    Seleziona Data e Orario
                </legend>

                <div class="card-body py-3">
                    <div class="row g-3">
                        <div class="col-md">
                            <label for="booking-date" class="form-label fw-semibold">
                                Data <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="booking-date" id="booking-date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required/>
                        </div>

                        <div class="col-md">
                            <label for="booking-time" class="form-label fw-semibold">
                                Orario <span class="text-danger">*</span>
                            </label>
                            <select name="booking-time" id="booking-time" class="form-select" required>
                                <option value="" disabled selected>Seleziona orario</option>
                                <option value="12:00">12:00</option>
                                <option value="12:30">12:30</option>
                                <option value="13:00">13:00</option>
                                <option value="13:30">13:30</option>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Step 2: Scegli i piatti -->
            <fieldset class="card my-4 rounded-4 border-0 shadow-sm">
                <legend class="card-header h5 mb-0 rounded-top">
                    <span class="badge bg-white me-2">2</span>
                    Scegli i piatti
                </legend>

                <div class="card-body py-4">

                    <!-- Esempio di piatti selezionabili -->
                    <?php foreach($templateParams["categories"] as $category): ?>
                    <section>
                        <h2 class="h6 text-muted mb-3"><?php echo htmlspecialchars($category["category_name"]); ?></h2>

                        <ul class="w-100 list-unstyled">
                            <?php foreach($dbh->getAllDishes($category["category_id"]) as $dish): 
                                if ($dish["stock"] <= 0) {
                                    continue;
                                }
                            ?>
                                <li class="mb-3 p-3 border rounded bg-light">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <strong class="d-block"><?php echo htmlspecialchars($dish["name"]); ?></strong>
                                            <span class="small text-muted d-block mb-2"><?php echo htmlspecialchars($dish["description"]); ?></span>
                                            <?php getTags($dbh->getDietaryTagsForDish($dish["dish_id"])); ?>
                                        </div>
                                        <div class="ms-4 text-end">
                                            <strong class="d-block fs-5 mb-2">€<?php echo htmlspecialchars($dish["price"]); ?></strong>
                                            <label for="<?php echo getIdFromName($dish["name"]) ?>" class="form-label small mb-1">Quantità</label>
                                                <input type="number" class="form-control" 
                                                    id="<?php echo getIdFromName($dish["name"]) ?>" name="<?php echo getIdFromName($dish["name"]) ?>" 
                                                    min="0" max="<?php echo htmlspecialchars($dish["stock"]); ?>" value="0" placeholder="0" 
                                                    data-price="<?php echo htmlspecialchars($dish["price"]); ?>"
                                                    onchange="aggiornaRiepilogo('<?php echo $dish["name"] ?>', this.id, this.value)"
                                                />
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                    </section>
                    <?php endforeach; ?>

                </div>
            </fieldset>
            
            <!-- Step 3: Note Aggiuntive (Opzionale) -->
            <fieldset class="card my-4 rounded-4 border-0 shadow-sm">
                <legend class="card-header h5 mb-0 rounded-top">
                    <span class="badge bg-white me-2">3</span>
                    Note Aggiuntive (Opzionale)
                </legend>

                <div class="card-body py-3">
                    <div class="row g-3">
                        <div class="col-md">
                            <label for="booking-notes" class="form-label fw-semibold">
                                Note o richieste speciali
                            </label>
                            <textarea name="booking-notes" id="booking-notes" class="form-control" cols="3" placeholder="Es. allergie, intolleranze, preferenze..."></textarea>
                        </div>
                    </div>
                </div>
            </fieldwset>
        </section>

        <!-- Aside -->
        <aside class="col-xl-5">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header">
                    Riepilogo Prenotazione
                </div>

                <div class="card-body py-3">
                    <div id="riepilogo-container">
                        <div id="riepilogo-items">
                            <div id="riepilogo-placeholder" class="text-center text-muted py-4">
                                <i class="bi bi-cart-x fs-1" aria-hidden="true"></i>
                                <p class="small mb-0 mt-2">Nessun piatto selezionato</p>
                            </div>

                            <div class="table-responsive d-none" id="riepilogo-table-wrapper">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th class="text-end">Quantità</th>
                                            <th class="text-end">Prezzo</th>
                                            <th class="text-end">Subtotale</th>
                                        </tr>
                                    </thead>
                                    <tbody id="riepilogo-table-body"></tbody>
                                </table>
                            </div>
                        </div>
                        <div id="riepilogo-total-value" class="d-flex justify-content-end gap-1 fw-bold pt-2 d-none">
                            <span class="me-1">Totale</span>
                            <span>€0,00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid mt-3">
                <input type="submit" class="btn btn-primary btn-lg rounded-4" value="Invia Ordine" />
            </div>
        </aside>
    </form>
</main>