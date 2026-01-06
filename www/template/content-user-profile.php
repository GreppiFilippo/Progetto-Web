<?php
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}

$selectedIds = $templateParams["user_selected_spec_ids"] ?? [];
$dietarySpecs = $templateParams["dietary_specs"] ?? [];
?>

<main class="container my-5">
    <h1 class="mb-4">
        <i class="bi bi-person-circle" aria-hidden="true"></i>
        Il Mio Profilo
    </h1>

    <div class="row">

        <section class="col-md-6 order-2 order-md-1 border rounded-3 shadow p-4 mb-4" aria-labelledby="prefs-title">
            <h2 id="prefs-title" class="h4 mb-3">Preferenze</h2>

            <!-- FEEDBACK ERRORE -->
            <?php if (!empty($templateParams["error"])): ?>
                <div class="alert border border-dark bg-white text-dark mb-3" role="alert" aria-live="assertive">
                    <p class="mb-1 fw-semibold">Non è stato possibile salvare le preferenze.</p>
                    <p class="mb-0">
                        <?php echo htmlspecialchars((string) $templateParams["error"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- FEEDBACK SUCCESSO -->
            <?php if (!empty($templateParams["success"])): ?>
                <div class="alert border border-dark bg-white text-dark mb-3" role="status" aria-live="polite">
                    <p class="mb-0 fw-semibold">Preferenze salvate correttamente.</p>
                </div>
            <?php endif; ?>

            <form action="user-profile.php" method="POST" class="mt-3" novalidate>
                <fieldset class="mb-3">
                    <legend class="h6 mb-2">Restrizioni alimentari</legend>

                    <?php if (empty($dietarySpecs)): ?>
                        <p class="mb-0">Nessuna preferenza disponibile al momento.</p>
                    <?php else: ?>
                        <?php foreach ($dietarySpecs as $spec): ?>
                            <?php
                            $id = (int) ($spec["dietary_spec_id"] ?? 0);
                            $name = (string) ($spec["dietary_spec_name"] ?? '');
                            $checked = in_array($id, $selectedIds, true) ? "checked" : "";
                            ?>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" id="diet_<?php echo $id; ?>" name="preferenze[]"
                                    value="<?php echo $id; ?>" <?php echo $checked; ?>>
                                <label class="form-check-label" for="diet_<?php echo $id; ?>">
                                    <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </fieldset>

                <button type="submit" class="btn btn-primary">
                    Salva preferenze
                </button>
            </form>
        </section>

        <aside class="col-md-5 order-1 order-md-2 border rounded-3 shadow p-4 mb-4 offset-md-1"
            aria-labelledby="profilo-utente">
            <div class="text-center">
                <i class="bi bi-person-circle fs-1" aria-hidden="true"></i>

                <h2 id="profilo-utente" class="h3 mt-3">
                    <?php
                    $first = (string) ($templateParams["user"]["first_name"] ?? '');
                    $last = (string) ($templateParams["user"]["last_name"] ?? '');
                    echo htmlspecialchars(trim($first . ' ' . $last), ENT_QUOTES, 'UTF-8');
                    ?>
                </h2>

                <p>
                    <strong>Membro da:</strong>
                    <?php
                    $regRaw = $templateParams["user"]["registration_date"] ?? null;

                    if ($regRaw) {
                        $regDate = new DateTime((string) $regRaw);

                        $mesi = [
                            1 => 'Gennaio',
                            2 => 'Febbraio',
                            3 => 'Marzo',
                            4 => 'Aprile',
                            5 => 'Maggio',
                            6 => 'Giugno',
                            7 => 'Luglio',
                            8 => 'Agosto',
                            9 => 'Settembre',
                            10 => 'Ottobre',
                            11 => 'Novembre',
                            12 => 'Dicembre'
                        ];

                        $mese = $mesi[(int) $regDate->format('n')] ?? '';
                        $anno = $regDate->format('Y');

                        echo htmlspecialchars(trim("$mese $anno"), ENT_QUOTES, 'UTF-8');
                    } else {
                        echo "—";
                    }
                    ?>
                </p>
            </div>
        </aside>

    </div>
</main>