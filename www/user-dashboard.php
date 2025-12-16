<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    http_response_code(403);
    require "login.php";
    exit();
}

$userId = $_SESSION["user_id"];
$templateParams["user"] = $dbh->getUserById($userId);  


if (isset($_GET["cancel_id"])) {
    $reservationId = (int)$_GET["cancel_id"];

    if ($reservationId > 0) {
        $dbh->deleteReservation($reservationId, $userId);
    }
    header("Location: user-dashboard.php");
    exit();
}

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

// se è presente show_all=1, mostra tutte le prenotazioni
$showAll = isset($_GET["show_all"]) && $_GET["show_all"] == "1";

// limite: 3 se non showAll, altrimenti NULL (o un numero alto)
$limit = $showAll ? null : 3;

$reservations = $dbh->getReservationsByUser($userId, $limit);;

// aggiungi items per ogni prenotazione (semplice e chiaro)
foreach ($reservations as &$r) {
    $r['items'] = $dbh->getReservationItems((int)$r['reservation_id']);
}
unset($r);

$templateParams["reservations"] = $reservations;
$templateParams["content"] = "template/user-dashboard-content.php";

require 'template/base-user.php';
?>