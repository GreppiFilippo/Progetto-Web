<?php
require_once 'bootstrap.php';

/** @var array<string, mixed> $templateParams */
$templateParams["titolo"] = "Mensa Campus - Home";
$templateParams["link_utili"] = [];

if (isUserLoggedIn()) {
    $templateParams["nav_items"] = array(
        getNewNavItem("Dashboard", "user-dashboard.php", "bi bi-speedometer2"),
        getNewNavItem("Menu", "menu.php", "bi bi-book"),
        getNewNavItem("Nuova Prenotazione", "user-bookings.php", "bi bi-calendar-check"),
        getNewNavItem("Profilo", "user-profile.php", "bi bi-person-circle"),
        getNewNavItem("Esci", "logout.php", "bi bi-box-arrow-right"),
    );

    $templateParams["link_utili"][] = array(
        "name" => "Menu",
        "link" => "menu.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Profilo",
        "link" => "user-profile.php",
    );
} else {
    $templateParams["nav_items"] = array(
        getNewNavItem("Home", "index.php", "bi bi-house-door"),
        getNewNavItem("Menu", "menu.php", "bi bi-book"),
        getNewNavItem("Accedi", "login.php", "bi bi-box-arrow-in-right")
    );
    
    $templateParams["link_utili"][] = array(
        "name" => "Menu",
        "link" => "menu.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Accedi",
        "link" => "login.php",
    );
    $templateParams["link_utili"][] = array(
        "name" => "Registrati",
        "link" => "register.php",
    );
}

$templateParams["content"] = "template/content-home.php";

require 'template/base-user.php';
?>