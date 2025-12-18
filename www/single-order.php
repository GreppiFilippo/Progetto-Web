<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    http_response_code(403);
    require "login.php";
    exit();
}

$templateParams["titolo"] = "Mensa Campus - Singolo Ordine";

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

$reservationId = isset($_GET["reservation_id"]) ? (int)$_GET["reservation_id"] : 0;
$userId = (int)$_SESSION["user_id"];

$reservation = $dbh->getReservationById($reservationId, $userId);
if (!$reservation) {
    http_response_code(404);
    die("Ordine non trovato oppure non autorizzato.");
}

if (isset($_GET["cancel_id"])) {
    $reservationId = (int)$_GET["cancel_id"];

    if ($reservationId > 0) {
        $dbh->deleteReservation($reservationId, $userId);
    }
    header("Location: user-dashboard.php");
    exit();
}

$items = $dbh->getReservationItemsDetailed($reservationId);
$tagsMap = $dbh->getDietaryTagsForReservation($reservationId);

$templateParams["reservation"] = $reservation;
$templateParams["items"] = $items;
$templateParams["tagsMap"] = $tagsMap;

$templateParams["content"] = "template/single-order-content.php";

require 'template/base-user.php';
?>