<!DOCTYPE html>
<html lang="it-IT">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Mensa Campus - Prenota il tuo pasto online" />
    <meta name="keywords" content="mensa, campus, prenotazione, pasto, online" />
    <title><?php echo htmlspecialchars((string)$templateParams["titolo"], ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <?php
      if(isset($templateParams["js"])):
          foreach($templateParams["js"] as $script):
      ?>
          <script src="<?php echo htmlspecialchars((string)$script, ENT_QUOTES, 'UTF-8'); ?>"></script>
      <?php
          endforeach;
      endif;
    ?>
  </head>

  <body class="container-fluid p-0 overflow-x-hidden">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" aria-label="Navigazione principale">
      <div class="container">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
          <i class="bi bi-shop fs-3 me-2" aria-hidden="true"></i>
          <span class="fw-bold">Mensa Campus</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Mostra menu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-md-center">
            <?php foreach($templateParams["nav_items"] as $nav_item): ?>
              <li class="nav-item">
                <a class="nav-link active text-white" aria-current="page" href="<?php echo htmlspecialchars((string)$nav_item["link"], ENT_QUOTES, 'UTF-8'); ?>">
                  <i class="<?php echo htmlspecialchars((string)$nav_item["iconClass"], ENT_QUOTES, 'UTF-8'); ?> me-1" aria-hidden="true"></i><?php echo htmlspecialchars((string)$nav_item["name"], ENT_QUOTES, 'UTF-8'); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>
    </nav>

    <?php
      if (isset($templateParams["content"])) {
        require $templateParams["content"];
      }
    ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h3 class="h5 my-3">Mensa Campus</h3>
            <p class="small">Sistema di prenotazione pasti per studenti e personale universitario.</p>
            <p class="small mb-0">&copy; 2025 Mensa Campus. Tutti i diritti riservati.</p>
          </div>
          <div class="col-lg-3">
            <nav aria-label="Link utili">
              <h4 class="h5 my-3">Link Utili</h4>
              <ul class="list-unstyled small">
                <?php foreach($templateParams["link_utili"] as $link): ?>
                  <li><a href="<?php echo htmlspecialchars((string)$link["link"], ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none"><?php echo htmlspecialchars((string)$link["name"], ENT_QUOTES, 'UTF-8'); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </nav>
          </div>
          <div class="col-lg-3">
            <h4 class="h5 my-3">Contatti</h4>
            <address>
              <ul class="list-unstyled small">
                <li class="text-white-50">
                  <i class="bi bi-envelope me-2" aria-hidden="true"></i>
                  <a href="mailto:info@mensacampus.it" class="text-decoration-none">info@mensacampus.it</a>
                </li>
                <li class="text-white-50">
                  <i class="bi bi-telephone me-2" aria-hidden="true"></i>
                  <a href="tel:+39051123456" class="text-decoration-none">+39 051 123456</a>
                </li>
              </ul>
            </address>
          </div>
        </div>
      </div>
    </footer>

  </body>
</html>
