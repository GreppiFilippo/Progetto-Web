<?php
if (!defined('IN_APP')) {
  http_response_code(404);
  exit;
}
?>
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
    <link rel="stylesheet" type="text/css" href="css/admin-style.css" />
  </head>
  <?php if (!empty($templateParams["scripts"])): ?>
    <?php foreach ($templateParams["scripts"] as $src): ?>
      <script src="<?php echo htmlspecialchars((string)$src, ENT_QUOTES, 'UTF-8'); ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>
  <body class="admin-page">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <!-- Navigation Admin -->
    <nav class="nav-admin navbar navbar-expand-md navbar-dark sticky-top" aria-label="Navigazione principale">
      <div class="container">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="admin-dashboard.php">
          <i class="bi bi-shop fs-3 me-2 text-white" aria-hidden="true"></i>
          <span class="fw-bold text-white">Admin Panel</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Mostra o nascondi il menu di navigazione">
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

    <footer class="bg-dark text-white py-4 text-center">
      <p class="mb-0">&copy; 2025 Mensa Campus-Admin Panel</p>
    </footer>
    
  </body>
</html>
