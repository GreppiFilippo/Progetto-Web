<?php
if (!defined('IN_APP')) {
    http_response_code(403);
    exit('Access denied');
}
?>
<main class="container my-5">
    <p class="visually-hidden">
    <span class="text-danger">*</span> Campo obbligatorio
    </p>

    <?php if (!empty($templateParams["errors"])): ?>
        <div
            class="alert border border-3 border-dark bg-white text-dark"
            role="alert"
            aria-live="assertive"
        >
            <p class="fw-bold mb-2">Errore</p>
            <ul class="mb-0">
                <?php foreach ($templateParams["errors"] as $error): ?>
                    <li><?php echo htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($templateParams["success"])): ?>
        <div
            class="alert border border-3 border-dark bg-white text-dark"
            role="alert"
            aria-live="assertive"
        >
            <p class="fw-bold mb-2">Operazione completata</p>
            <p class="mb-0">
                Registrazione avvenuta con successo.
                Ora puoi
                <a href="login.php" class="fw-bold text-decoration-underline text-dark">
                    accedere al tuo account
                </a>.
            </p>
        </div>
    <?php endif; ?>

    <form action="register.php" method="post" aria-labelledby="form-title"  class="border rounded-3 p-4 shadow-sm">
    <fieldset>
        <legend class="visually-hidden">Form di registrazione alla mensa campus</legend>

        <div class="text-center">
        <i class="bi bi-person-fill-add fs-1" aria-hidden="true"></i>
        <h1 id="form-title" class="h3 mt-3">Crea il tuo Account</h1>
        <p class="text-muted">Compila i campi per registrarti</p>
        </div>

        <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <label for="nome" class="form-label">Nome<span class="text-danger">*</span></label>
            <input type="text" id="nome" name="nome" class="form-control"
                    placeholder="Inserire nome..." autocomplete="given-name" required />
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label for="cognome" class="form-label">Cognome<span class="text-danger">*</span></label>
            <input type="text" id="cognome" name="cognome" class="form-control"
                    placeholder="Inserire cognome..." autocomplete="family-name" required />
        </div>
        </div> 

        <div class="mb-3">
        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>

        <div class="input-group">
            <span class="input-group-text" id="visible-addon"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email"
                placeholder="Inserire email..." autocomplete="email"
                aria-describedby="visible-addon" required />
        </div>
        </div>

        <div class="mb-3">
        <label for="password" class="form-label">Password<span class="text-danger">*</span></label>

        <div class="input-group">
            <span class="input-group-text" id="visible-addon-password">
                <i class="bi bi-file-lock" aria-hidden="true"></i>
            </span>

            <input type="password" class="form-control" id="password" name="password"
                    placeholder="Inserire password..." aria-describedby="visible-addon-password" autocomplete="new-password" required />
            </div>
        </div>

        <div class="mb-3">
        <label for="confermapassword" class="form-label">Conferma Password<span class="text-danger">*</span></label>

        <div class="input-group">
            <span class="input-group-text" id="visible-addon-confirm">
            <i class="bi bi-file-lock2" aria-hidden="true"></i>
            </span>

            <input type="password" class="form-control" id="confermapassword" name="confermapassword"
                placeholder="Ripeti la password..." aria-describedby="visible-addon-confirm" autocomplete="new-password" required />
        </div>
        </div>

        <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-person-fill-add me-2" aria-hidden="true"></i> Registrati
        </button>
        </div>
        <div class="d-flex justify-content-center gap-2">
        <p class="mb-0">Hai già un account?</p>
        <a class="text-decoration-none fw-bold" href="login.html">Accedi al tuo account</a>
        </div>
    </fieldset>
    </form>
</main>
