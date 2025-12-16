<?php
    require_once 'bootstrap.php';

    // Admin template access control
    if (!isUserLoggedIn()) {
        http_response_code(403);
        require "login.php";
        exit();
    }
 
    if (!isAdmin()) {
        http_response_code(401);
        require "not-authorized.php";
        exit();
    }

    // Base Template
    $templateParams["titolo"] = "Mensa Campus - Aggiunta piatto al menù";

    // Navigation Items
    $templateParams["nav_items"][] = getNewNavItem("Dashboard", "admin-dashboard.php", "bi bi-speedometer2");
    $templateParams["nav_items"][] = getNewNavItem("Gestione Menù", "admin-menu.php", "bi bi-bi-book");
    $templateParams["nav_items"][] = getNewNavItem("Prenotazioni", "admin-bookings.php", "bi bi-calendar-check");
    $templateParams["nav_items"][] = getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right");

    $templateParams["content"] = "template/content-admin-add-dish.php";

    require 'template/base-admin.php';
?>