<main class="container my-5">
    <h1 class="mb-4"><i class="bi bi-person-circle"></i> Il Mio Profilo</h1>
    <div class="row">
    <section class="col-md-6 order-2 order-md-1 border rounded-3 shadow p-4 mb-4" aria-labelledby="prefs-title">
        <h2 id="prefs-title">Preferenze</h2>
        <form action="#" class="mt-3">
        <fieldset>
            <legend>Restrizioni alimentari</legend>

            <?php foreach($templateParams["dietary_spec_names"] as $dietary_spec_name): ?>

            <?php
                $id = strtolower(str_replace(' ', '', $dietary_spec_name));
            ?>

            <div class="form-check">
            <input type="checkbox" class="form-check-input" id="<?php echo $id?>" name="preferenze" value="<?php echo $id?>">
            <label class="form-check-label" for="<?php echo $id ?>"><?php echo $dietary_spec_name?></label>
            </div>
            <?php endforeach; ?>
        </fieldset>

        <button type="submit" class="btn btn-success mt-3">
            <i class="bi bi-save" aria-hidden="true"></i>
            <span class="ms-1">Salva Preferenze</span>
        </button>
        </form>
    </section>
    <aside class="col-md-5 order-1 order-md-2 border rounded-3 shadow p-4 mb-4 offset-md-1"
            aria-labelledby="profilo-utente">
        <div class="text-center">
        <i class="bi bi-person-circle fs-1"></i>
        <h2 id="profilo-utente" class="h3 mt-3">Mario Rossi</h2>
        <p class="text-muted">Studente</p>
        <p><b>Membro dal:</b> Settembre 2024.</p>
        </div>
    </aside>
    </div>
</main>