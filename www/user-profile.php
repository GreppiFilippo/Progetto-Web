<?php
require_once 'bootstrap.php';

// Base Template
$templateParams["titolo"] = "Mensa Campus - Profilo Utente";

$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
    getNewNavItem("Nuova Prenotazione", "user-booking.php", "bi bi-calendar-plus me-1"),
    getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
    getNewNavItem("Esci", "#", "bi bi-box-arrow-right me-1")
);
$templateParams["link_utili"][] = array(
    "name" => "Menu",
    "link" => "menu.php",
);
$templateParams["link_utili"][] = array(
    "name" => "Profilo",
    "link" => "user-profile.php",
);

$templateParams["content"] = "template/content-user-profile.php";

require 'template/base-user.php';
?>