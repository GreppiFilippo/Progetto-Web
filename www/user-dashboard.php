<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    http_response_code(403);
    require "login.php";
    exit();
}

$userId = $_SESSION["user_id"];
$templateParams["user"] = $dbh->getUserById($userId);   

$counts = $dbh->getReservationCountsByUser($userId);

$templateParams["titolo"] = "Mensa Campus - Prenotazioni";
$templateParams["active_count"] = (int)($counts['active_count'] ?? 0);
$templateParams["completed_count"] = (int)($counts['completed_count'] ?? 0);



$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
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

$reservations = $dbh->getReservationsByUser($userId, 5);

// aggiungi items per ogni prenotazione (semplice e chiaro)
foreach ($reservations as &$r) {
    $r['items'] = $dbh->getReservationItems((int)$r['reservation_id']);
}
unset($r);

$templateParams["reservations"] = $reservations;
$templateParams["content"] = "template/user-dashboard-content.php";

require 'template/base-user.php';
?>