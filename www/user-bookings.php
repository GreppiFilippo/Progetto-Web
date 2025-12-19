<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    http_response_code(403);
    require "login.php";
    exit();
}

$templateParams["titolo"] = "Mensa Campus - Prenotazioni";

$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
    getNewNavItem("Menu", "menu.php", "bi bi-book"),
    getNewNavItem("Nuova Prenotazione", "user-bookings.php", "bi bi-calendar-check"),
    getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
    getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right")
);

$templateParams["link_utili"][] = array(
    "name" => "Menu",
    "link" => "menu.php",
);
$templateParams["link_utili"][] = array(
    "name" => "Profilo",
    "link" => "user-profile.php",
);

$templateParams["categories"] = $dbh->getAllCategories();
$templateParams["time_slots"] = $dbh->getTimeSlotsByDate(date('Y-m-d'));
$templateParams["content"] = "template/user-bookings-content.php";
// include JS for booking page
$templateParams["js"][] = "js/user-bookings.js";

require 'template/base-user.php';
?>