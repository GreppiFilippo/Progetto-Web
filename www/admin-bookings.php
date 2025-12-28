<?php
    require_once "bootstrap.php";

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


    $templateParams["nav_items"] = array(
        getNewNavItem("Dashboard", "admin-dashboard.php", "bi bi-speedometer2 me-1"),
        getNewNavItem("Gestione menu", "admin-menu.php", "bi bi-book me-1"),
        getNewNavItem("Prenotazioni", "admin-bookings.php", "bi bi-calendar-check me-1"),
        getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right me-1")
    );

    $templateParams["titolo"] = "Mensa Campus - Prenotazioni";
    $templateParams["content"] = "template/content-admin-bookings.php";

    require "template/base-admin.php"
?>