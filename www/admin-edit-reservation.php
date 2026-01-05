<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

// Controllo parametro obbligatorio
if (!isset($_GET['reservation_id']) && !isset(($_GET['status']))) {
    http_response_code(400);
    require "not-found.php";
    exit();
}

$reservation_id = (int) $_GET['reservation_id'];
$status = (string) $_GET['status'];
$user_id = $dbh->getUserByReservation($reservation_id);
$reservation = $dbh->getReservationById($reservation_id, $user_id);

// NAV
$templateParams["nav_items"] = array(
    getNewNavItem("Dashboard", "admin-dashboard.php", "bi bi-speedometer2 me-1"),
    getNewNavItem("Gestione menu", "admin-menu.php", "bi bi-book me-1"),
    getNewNavItem("Prenotazioni", "admin-bookings.php", "bi bi-calendar-check me-1"),
    getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right me-1")
);

// PAGE DATA
$templateParams["titolo"] = "Mensa Campus - Modifica Prenotazione";
$templateParams["content"] = "template/content-admin-edit-reservation.php";
$templateParams["js"] = array("js/edit-reservation.js");

require "template/base-admin.php";
?>