<?php
if (!defined('IN_APP')) {
    http_response_code(403);
    exit('Access denied');
}
?>
<main class="container my-5">
    <h1 class="mb-4"><i class="bi bi-person-circle"></i> Il Mio Profilo</h1>
    <div class="row">
        <?php
            $selectedIds = $templateParams["user_selected_spec_ids"] ?? [];
        ?>

        <section class="col-md-6 order-2 order-md-1 border rounded-3 shadow p-4 mb-4"
                aria-labelledby="prefs-title">

        <h2 id="prefs-title" class="h4 mb-3">Preferenze</h2>

        <!-- Messaggi SOLO bianco/nero -->
        <?php if (!empty($templateParams["error"])): ?>
            <div class="alert border border-dark bg-white text-dark mb-3" role="alert" aria-live="assertive">
            <p class="mb-1 fw-semibold">Non è stato possibile salvare le preferenze.</p>
            <p class="mb-0"><?php echo htmlspecialchars((string)$templateParams["error"], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <form action="user-profile.php" method="POST" class="mt-3" novalidate>

            <fieldset class="mb-3">
                <legend class="h6 mb-2">Restrizioni alimentari</legend>

                <?php foreach ($templateParams["dietary_specs"] as $spec): ?>
                    <?php
                    $id = (int)$spec["dietary_spec_id"];
                    $name = $spec["dietary_spec_name"];
                    $checked = in_array($id, $selectedIds, true) ? "checked" : "";
                    ?>
                    <div class="form-check mb-1">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="diet_<?php echo $id; ?>"
                        name="preferenze[]"
                        value="<?php echo $id; ?>"
                        <?php echo $checked; ?>
                    >
                    <label class="form-check-label" for="diet_<?php echo $id; ?>">
                        <?php echo htmlspecialchars((string)$name, ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                    </div>
                <?php endforeach; ?>
            </fieldset>

            <button type="submit" class="btn btn-dark">
            <span>Salva preferenze</span>
            </button>
        </form>
        </section>
        <aside class="col-md-5 order-1 order-md-2 border rounded-3 shadow p-4 mb-4 offset-md-1"
                aria-labelledby="profilo-utente">
            <div class="text-center">
            <i class="bi bi-person-circle fs-1"></i>
            <h2 id="profilo-utente" class="h3 mt-3"><?php echo htmlspecialchars($templateParams["user"]["first_name"] . ' ' . $templateParams["user"]["last_name"], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p>
                <b>Membro da:</b>
                <?php
                    $regDate = new DateTime($templateParams["user"]["registration_date"]);
                    setlocale(LC_TIME, 'it_IT.UTF-8');
                    echo htmlspecialchars((string)$regDate->format('F Y'), ENT_QUOTES, 'UTF-8');
                ?>
            </p>

            </div>
        </aside>
    </div>
</main>
